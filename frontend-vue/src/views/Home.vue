<template>
  <div class="home-page">
    <home-banner />
    <div class="container page">
      <div class="row">
        <div class="col-md-9">
          <h2>Top Feeds</h2>
          <div class="top-feeds">
            <common-feed
              :active-tab-id="activeTabId"
              :is-loading="isLoading"
              :feed="topRatedFeed"
              :items-per-page="topRatedFeed.articlesCount"
              tabs=""
              current-page=""
            />
          </div>
        </div>
        <div class="col-md-3">
          <home-tags @tag-selected="onTagFeedActivated" />
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <common-feed
            :tabs="tabs"
            :active-tab-id="activeTabId"
            :is-loading="isLoading"
            :feed="activeFeed"
            :items-per-page="itemsPerPage"
            :current-page="currentPage"
            @page-changed="onPageChanged"
            @tab-changed="onTabChanged"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";

import CommonFeed, { IFeedTab } from "@/components/CommonFeed.vue";
import HomeBanner from "@/components/HomeBanner.vue";
import HomeTags from "@/components/HomeTags.vue";
import IPagination, {
  DEFAULT_ITEMS_PER_PAGE,
  DEFAULT_START_PAGE
} from "@/services/common/IPagination";
import TopRatedArticles from "@/services/common/TopRatedArticles";
import { IArticleList } from "@/services/realWorldApi/models";
import Article from "@/store/modules/Article";
import User from "@/store/modules/User";

enum FeedType {
  Global = "Global",
  My = "My",
  Tag = "Tag"
}

@Component({
  components: {
    HomeBanner,
    HomeTags,
    CommonFeed
  }
})
export default class Home extends Vue {
  feedTypes: typeof FeedType = FeedType;
  TOP_RATED_COUNT = 3;
  isLoading = false;
  currentPage = DEFAULT_START_PAGE;
  itemsPerPage = DEFAULT_ITEMS_PER_PAGE;

  activeTabId: FeedType = FeedType.My;
  activeFeed: IArticleList = { articles: [], articlesCount: 0 };
  topRatedFeed: IArticleList = { articles: [], articlesCount: 0 };
  activeTag: string | null = null;

  get isLoggedIn(): boolean {
    return User.isLoggedIn;
  }

  get tabs(): IFeedTab[] {
    const res: IFeedTab[] = [];

    if (this.isLoggedIn) {
      res.push({
        id: FeedType.My,
        title: "Your Feed"
      });
    }

    res.push({
      id: FeedType.Global,
      title: "Global Feed"
    });

    if (this.activeTag && this.activeTabId === FeedType.Tag) {
      res.push({
        id: FeedType.Tag,
        title: `#${this.activeTag}`
      });
    }
    return res;
  }

  async mounted(): Promise<void> {
    this.activeTabId = this.isLoggedIn ? FeedType.My : FeedType.Global;
    await this.fetchFeed();
  }

  async onTabChanged(tabId: FeedType): Promise<void> {
    switch (tabId) {
      case FeedType.My:
      case FeedType.Global:
        this.activeTabId = tabId;
        this.currentPage = DEFAULT_START_PAGE;
        await this.fetchFeed();
        break;
      case FeedType.Tag:
        await this.onTagFeedActivated(this.activeTag || "");
        break;

      default:
        throw new Error(`Unexpected tabId: ${tabId}`);
    }
  }

  async onTagFeedActivated(tag: string): Promise<void> {
    this.activeTabId = FeedType.Tag;
    this.currentPage = DEFAULT_START_PAGE;
    this.activeTag = tag;
    await this.fetchFeed();
  }

  async onPageChanged(page: number): Promise<void> {
    this.currentPage = page;
    await this.fetchFeed();
  }

  async fetchFeed(): Promise<void> {
    this.isLoading = true;
    await this.topRated(this.TOP_RATED_COUNT);
    try {
      const pagination: IPagination = {
        limit: this.itemsPerPage,
        offset: (this.currentPage - 1) * this.itemsPerPage
      };

      const activeFeed = await this.getCurrentFeed(pagination);

      if (activeFeed) {
        this.removeDuplicates(activeFeed);
        this.activeFeed = activeFeed;
      }
    } finally {
      this.isLoading = false;
      window.scrollTo(0, 0);
    }
  }

  removeDuplicates(activeFeed: IArticleList): void {
    let i = activeFeed.articles.length;
    while (i--) {
      this.topRatedFeed.articles.forEach(topArticle => {
        if (topArticle.slug === activeFeed.articles[i].slug) {
          activeFeed.articles.splice(i, 1);
          activeFeed.articlesCount--;
        }
      });
    }
  }

  async getCurrentFeed(pagination: IPagination): Promise<IArticleList> {
    switch (this.activeTabId) {
      case FeedType.Global:
        return await Article.getList({ ...pagination });
      case FeedType.Tag:
        return await Article.getList({
          tag: this.activeTag as string,
          ...pagination
        });
      case FeedType.My:
        return await Article.getFeed(pagination);
    }
  }

  async topRated(count: number): Promise<void> {
    const pagination: IPagination = {
      limit: Number.MAX_VALUE,
      offset: 0
    };

    let activeFeed = await this.getCurrentFeed(pagination);

    activeFeed = activeFeed
      ? TopRatedArticles(activeFeed, count)
      : { articles: [], articlesCount: 0 };

    this.topRatedFeed = activeFeed;
  }
}
</script>

<style scoped>
.top-feeds {
  padding: 15px;
  margin-bottom: 15px;
  background: rgb(213, 255, 0);
  background: linear-gradient(
    90deg,
    rgba(213, 255, 0, 1) 0%,
    rgba(255, 255, 255, 0) 85%
  );
}
</style>
