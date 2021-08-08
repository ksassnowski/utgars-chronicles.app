class Emitter {
    private events = {};

    public on(event: string, callback) {
        this.events[event] = this.events[event] || [];
        this.events[event].push(callback);
    }

    public off(event: string, callback) {
        if (!this.events[event]) {
            return;
        }

        this.events[event] = this.events[event].filter((fn) => fn === callback);
    }

    public trigger(event: string, payload: any) {
        if (!this.events[event]) {
            console.warn(`No event listeners registered for event ${event}`);
            return;
        }

        this.events[event].forEach((callback) => callback(payload));
    }
}

let emitter: Emitter|null = null;

export function useEmitter() {
    emitter = emitter || new Emitter();

    return emitter;
}
