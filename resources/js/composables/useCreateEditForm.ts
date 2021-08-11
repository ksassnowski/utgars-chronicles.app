import { watch } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { pick, noop } from "lodash";

export function useCreateEditForm(
    item,
    fields: string[],
    createRoute: string,
    updateRoute: () => string,
    position = null
) {
    const editing = !!item.value.id;
    const method = editing ? "put" : "post";
    const route = editing ? updateRoute() : createRoute;

    const payload = pick(item.value, fields);
    if (!editing && position) {
        payload.position = position;
    }

    const form = useForm(payload);
    const submit =
        (callback = noop) =>
        () => {
            form.submit(method, route, {
                only: ["errors", "history"],
                onSuccess: () => {
                    if (method === "post") {
                        form.reset(...fields);
                    }

                    callback();
                },
            });
        };

    watch(item, (newItem) => {
        fields.forEach((field) => (form[field] = newItem[field]));
    });

    return { form, submit };
}
