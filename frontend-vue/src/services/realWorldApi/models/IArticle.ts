import IProfile from "./IProfile";
export default interface IArticle {
  slug: string;
  title: string;
  description: string;
  body: string;
  tagList: string[];
  categoryId: number;
  createdAt: Date;
  updatedAt: Date;
  favorited: boolean;
  favoritesCount: number;
  author: IProfile;
}
