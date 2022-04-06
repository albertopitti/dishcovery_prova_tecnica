<template>
  <div v-if="isLoading">
    <common-loader :size="7" />
  </div>
  <div v-else class="sidebar">
    <p>Popular Tags ({{ count }}) </p>

    <div class="tag-list">
      <a
        v-for="tag in tags"
        :key="tag"
        class="tag-pill tag-default"
        href="#"
        @click.prevent="selectTag(tag)"
      >
        {{ tag }}
      </a>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Emit, Vue } from "vue-property-decorator";

import CommonLoader from "@/components/CommonLoader.vue";
import Tags from "@/store/modules/Tags";

@Component({
  components: { CommonLoader }
})
export default class HomeTags extends Vue {
  tags: string[] = [];
  count = 0;
  isLoading = false;

  async mounted(): Promise<void> {
    await this.getTags();
  }

  async getTags(): Promise<void> {
    this.isLoading = true;
    try {
      const tags = await Tags.get();
      this.tags = tags.tags;
      this.count = tags.count;
    } finally {
      this.isLoading = false;
    }
  }

  @Emit("tag-selected")
  selectTag(tag: string): string {
    return tag;
  }
}
</script>
