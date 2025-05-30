import { ref } from "vue";
import { InertiaForm } from "@inertiajs/vue3";
import { noop } from "lodash";

export function useEditMode<T>(
    form: InertiaForm<T>,
    route: string,
    reloadProps: string[] | null = null,
    method: string = "post"
) {
    const editing = ref(false);
    const startEditing = () => (editing.value = true);
    const stopEditing = () => (editing.value = false);
    const submit =
        (callback: () => any = noop) =>
        () => {
            form.submit(method, route, {
                only: reloadProps,
                onSuccess: () => {
                    stopEditing();
                    callback();
                },
            });
        };

    return { editing, startEditing, stopEditing, submit };
}
