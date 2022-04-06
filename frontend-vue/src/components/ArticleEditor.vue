<template>
  <div class="editor-page">
    <div class="container page">
      <div class="row">
        <div class="col-md-10 offset-md-1 col-xs-12">
          <common-errors-list :errors="errors" />

          <form>
            <fieldset :disabled="isLoading">
              <fieldset class="form-group">
                <input
                  v-model="title"
                  type="text"
                  class="form-control form-control-lg"
                  placeholder="Article Title"
                  required="true"
                />
              </fieldset>
              <fieldset class="form-group">
                <input
                  v-model="description"
                  type="text"
                  class="form-control"
                  placeholder="What's this article about?"
                  required="true"
                />
              </fieldset>
              <fieldset class="form-group">
                <textarea
                  v-model="body"
                  class="form-control"
                  rows="8"
                  placeholder="Write your article (in markdown)"
                  required="true"
                ></textarea>
              </fieldset>
              <fieldset class="form-group">
                <input
                  v-model="tagList"
                  type="text"
                  class="form-control"
                  placeholder="Enter tags"
                  required="true"
                />
                <div class="tag-list">
                  <span
                    v-for="tag in existingTagList"
                    :key="tag"
                    class="tag-default tag-pill ng-binding ng-scope"
                  >
                    <i class="ion-close-round" @click="removeTag(tag)"></i>
                    {{ tag }}
                  </span>
                </div>
              </fieldset>
              <fieldset class="form-group">
                <select
                  v-model="categoryId"
                  class="form-control"
                  required="true"
                  autocomplete="off"
                >
                  <option selected value="">
                    Select category...
                  </option>
                  <option
                    v-for="category in databaseCategories"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.name }}
                  </option>
                </select>
              </fieldset>
              <button
                class="btn btn-lg pull-xs-right btn-primary"
                type="button"
                @click="publish"
              >
                Publish Article
              </button>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";

import CommonErrorsList from "@/components/CommonErrorsList.vue";
import { IArticle } from "@/services/realWorldApi/models";
import Article from "@/store/modules/Article";
import Categories from "@/store/modules/Categories";
import { isArrayOfStrings } from "@/utils/ArrayUtils";
import { notifySuccess } from "@/utils/NotificationUtils";

@Component({
  components: {
    CommonErrorsList
  }
})
export default class ArticleEditor extends Vue {
  @Prop() readonly article: IArticle | undefined;
  isLoading = false;
  title = this.article?.title || "";
  description = this.article?.description || "";
  body = this.article?.body || "";
  tagList = "";
  categoryId = this.article?.categoryId || 0;
  existingTagList: string[] = this.article?.tagList?.slice() || [];
  databaseCategories: string[] = [];
  errors: string[] = [];

  async mounted(): Promise<void> {
    await this.getCategories();
  }

  async getCategories(): Promise<void> {
    this.isLoading = true;
    try {
      this.databaseCategories = await Categories.get();
    } finally {
      this.isLoading = false;
    }
  }

  removeTag(tag: string): void {
    const index = this.existingTagList.indexOf(tag);
    this.existingTagList.splice(index, 1);
  }

  async publish(): Promise<void> {
    this.errors = [];

    this.isLoading = true;
    try {
      let article;
      if (this.article) {
        let newTagsList = this.existingTagList.slice();
        if (this.tagList.length > 0) {
          newTagsList = newTagsList.concat(this.tagList.split(","));
        }
        article = await Article.update({
          slug: this.article.slug,
          params: {
            title: this.title,
            description: this.description,
            body: this.body,
            category_id: this.categoryId,
            tagList: newTagsList
          }
        });
        notifySuccess("Article was successfully edited, redirecting...");
      } else {
        article = await Article.create({
          title: this.title,
          body: this.body,
          tagList: this.tagList.split(","),
          categoryId: this.categoryId,
          description: this.description
        });
        notifySuccess("Article was successfully created, redirecting...");
      }

      await this.$router.push({
        name: this.$routesNames.articleView,
        params: { slug: article.slug }
      });
    } catch (e) {
      if (isArrayOfStrings(e)) {
        this.errors = e;
      } else {
        throw e;
      }
    } finally {
      this.isLoading = false;
    }
  }
}
</script>

<style scoped>

</style>
