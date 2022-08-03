import { usePage } from "@inertiajs/inertia-vue3";
import includes from "lodash/includes";

export function useFeatureFlags() {
    const features = usePage<{ features: string[] }>().props.value.features;

    return (feature: string): boolean => includes(features, feature);
}
