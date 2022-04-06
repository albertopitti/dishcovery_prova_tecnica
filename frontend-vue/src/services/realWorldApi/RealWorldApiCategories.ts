import RealWorldApiInstance from "./RealWorldApiBase";

const CATEGORIES_PATH = "/categories";

export const CategoriesGet = async (): Promise<string[]> => {
  const res = await RealWorldApiInstance.get(CATEGORIES_PATH);
  return res?.data?.categories as string[];
};
