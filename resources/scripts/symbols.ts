import {InjectionKey, Ref} from "vue";

import {Focus, History, PaletteItem} from "@/types";

export const HistoryKey: InjectionKey<History> = Symbol();
export const ChannelKey: InjectionKey<string> = Symbol();
export const PaletteKey: InjectionKey<Ref<PaletteItem[]>> = Symbol();
export const FociKey: InjectionKey<Ref<Focus[]>> = Symbol();
