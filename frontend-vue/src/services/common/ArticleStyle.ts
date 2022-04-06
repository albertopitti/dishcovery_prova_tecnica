export default (categoryId: number): string => {
  switch (categoryId) {
    case 1:
      return "article-happy";
    case 2:
      return "article-cool";
    case 3:
      return "article-sad";
    default:
      return "";
  }
};
