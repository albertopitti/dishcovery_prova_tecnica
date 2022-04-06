import { Action, getModule, Module, VuexModule } from "vuex-module-decorators";

import { CategoriesGet } from "@/services/realWorldApi/RealWorldApiCategories";

import store from "../index";
import modulesNames from "../modulesNames";

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.categories })
class Categories extends VuexModule {
  @Action({ rawError: true })
  async get(): Promise<string[]> {
    return await CategoriesGet();
  }
}

export default getModule(Categories);
