import { usePage } from "@inertiajs/vue3";
import includes from "lodash/includes";

export function useFeatureFlags() {
    const features = usePage<{ features: string[] }>().props.features;

    return (feature: string): boolean => includes(features, feature);
}
