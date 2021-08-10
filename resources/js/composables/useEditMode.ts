import { ref } from "vue";
import { InertiaForm } from "@inertiajs/inertia-vue3";

export function useEditMode<T>(
    form: InertiaForm<T>,
    route: string,
    reloadProps: string[] | null = null,
    method: string = "post"
) {
    const editing = ref(false);
    const startEditing = () => editing.value = true;
    const stopEditing = () => editing.value = false;
    const submit = (callback: (() => any | null) = null) => {
        form.submit(method, route, {
            only: reloadProps,
            onSuccess: () => {
                form.reset();
                stopEditing();

                if (callback) {
                    callback();
                }
            },
        });
    };

    return { editing, startEditing, stopEditing, submit };
}
