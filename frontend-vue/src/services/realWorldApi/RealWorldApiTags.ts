import RealWorldApiInstance from "./RealWorldApiBase";

const TAGS_PATH = "/tags";

export const TagsGet = async (): Promise<{ tags: []; count: number }> => {
  const res = await RealWorldApiInstance.get(TAGS_PATH);
  return res?.data as { tags: []; count: number };
};
