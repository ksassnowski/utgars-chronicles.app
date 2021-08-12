type EventHandler = (args: any) => void;
type HandlerMap = { [event: string]: EventHandler[] };

// Poor man's pub-sub
class Emitter {
    private handlers: HandlerMap = {};

    public on(event: string, handler: EventHandler) {
        if (!this.handlers.hasOwnProperty(event)) {
            this.handlers[event] = [];
        }

        this.handlers[event].push(handler);

        return () => this.off(event, handler);
    }

    public off(event: string, handler: EventHandler) {
        if (!this.handlers[event]) {
            return;
        }

        this.handlers[event] = this.handlers[event].filter(
            (h) => h !== handler
        );
    }

    public trigger(event: string, payload: any = null) {
        if (!this.handlers[event]) {
            return;
        }

        this.handlers[event].forEach((handler) => handler(payload));
    }
}

export const useEmitter = (() => {
    // `emitter` is a singleton without polluting the
    // global namespace since it is scoped to this closure.
    const emitter = new Emitter();

    return () => emitter;
})();
