<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineProps<{
    processing?: boolean;
    submitLabel: string;
    cancelHref: string | { url: string };
}>();
</script>

<template>
    <div
        class="sticky bottom-0 z-10 -mx-4 mt-2 flex flex-wrap items-center gap-3 border-t border-neutral-300 bg-[#f3f3f1] px-4 py-4"
    >
        <Button
            type="submit"
            :disabled="processing"
            class="rounded-none bg-neutral-950 px-4 hover:bg-neutral-800"
        >
            {{ processing ? 'Saving...' : submitLabel }}
        </Button>
        <Button
            variant="outline"
            class="rounded-none border-neutral-400 bg-white"
            as-child
        >
            <Link :href="cancelHref">Cancel</Link>
        </Button>
    </div>
</template>
