export enum CardType {
    Dark = "dark",
    Light = "light",
}

export enum PaletteType {
    Yes = "yes",
    No = "no",
}

export enum MicroscopeGameMode {
    BaseGame = 1,
    Echo = 2,
}

export enum AgentPowers {
    Ordinary = "Ordinary",
    Extraordinary = "Extraordinary",
    Omnipotent = "Omnipotent",
}

export interface EchoGameSettings {
    faction_1_name: string|null;
    faction_1_description: string|null;
    faction_2_name: string|null;
    faction_2_description: string|null;
    agent_powers: AgentPowers|null;
}

export interface Focus {
    id: number;
    name: string;
}

export interface PaletteItem {
    id?: number;
    name: string;
    history_id?: number;
    type: PaletteType;
}

export interface Legacy {
    id: number;
    name: string;
}

export interface Scene {
    id?: number;
    question: string;
    scene: string|null;
    answer: string|null;
    position: number;
    type: CardType;
}

export interface Event {
    id?: number;
    name: string;
    position: number;
    type: CardType;
    scenes: Array<Scene>
}

export interface Period {
    id?: number;
    name: string;
    position: number;
    type: CardType;
    events: Array<Event>;
}

export interface History {
    id: number;
    public: boolean;
    name: string;
    periods: Array<Period>;
    game_mode: MicroscopeGameMode;
}

export interface Player {
    id: string;
    name: string
}

export interface Lfg {
    id?: number;
    title: string;
    start_date: number;
    slots: number;
    availableSlots: number;
}
