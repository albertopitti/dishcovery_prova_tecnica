interface IModulesNames {
  user: string;
  article: string;
  profile: string;
  tags: string;
  categories: string;
}

const modulesNames: Readonly<IModulesNames> = {
  user: "user",
  article: "article",
  profile: "profile",
  tags: "tags",
  categories: "categories"
};

export default modulesNames;
