<script setup lang="ts">
defineProps<{
    title: string;
    description: string;
}>();
</script>

<template>
    <div
        class="flex flex-col items-center justify-center border border-neutral-900 bg-white px-6 py-14 text-center shadow-[4px_4px_0_0_rgba(23,23,23,0.08)]"
    >
        <div
            v-if="$slots.icon"
            class="mb-4 flex size-12 items-center justify-center border border-neutral-300 bg-neutral-50 text-neutral-500"
        >
            <slot name="icon" />
        </div>
        <h3 class="text-base font-medium">{{ title }}</h3>
        <p class="mt-1 max-w-md text-sm text-muted-foreground">{{ description }}</p>
        <div v-if="$slots.action" class="mt-5">
            <slot name="action" />
        </div>
    </div>
</template>
