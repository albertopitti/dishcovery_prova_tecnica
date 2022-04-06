import { IArticleList } from "@/services/realWorldApi/models";

export default (articleList: IArticleList, length: number): IArticleList => {
  articleList.articles.sort((a, b) => {
    if (a.favoritesCount === b.favoritesCount)
      return a.createdAt < b.createdAt ? 1 : 0;
    return a.favoritesCount < b.favoritesCount ? 1 : 0;
  });
  articleList.articles.splice(length, articleList.articlesCount);
  articleList.articlesCount = length;
  return articleList;
};
