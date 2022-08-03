export enum CardType {
    Dark = "dark",
    Light = "light",
}

export enum PaletteType {
    Yes = "yes",
    No = "no",
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
    periods: Array<Period>
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
