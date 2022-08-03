import { InjectionKey } from "vue";

import { History } from "@/types";

export const HistoryKey: InjectionKey<History> = Symbol();
export const ChannelKey: InjectionKey<string> = Symbol();
