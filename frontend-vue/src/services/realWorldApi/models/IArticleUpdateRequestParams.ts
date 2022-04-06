export default interface IArticleUpdateRequestParams {
  title?: string;
  description?: string;
  body?: string;
  tagList?: string[];
  category_id?: number;
}
