import {InjectionKey, Ref} from "vue";

import {EchoGameSettings, History} from "@/types";

export const HistoryKey: InjectionKey<History> = Symbol();
export const ChannelKey: InjectionKey<string> = Symbol();
export const EchoSettingsKey: InjectionKey<Ref<EchoGameSettings>> = Symbol();
