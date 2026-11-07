<script setup lang="ts">
defineProps<{
    countLabel?: string;
}>();
</script>

<template>
    <section class="border border-neutral-900 bg-white shadow-[4px_4px_0_0_rgba(23,23,23,0.08)]">
        <div
            class="flex flex-col gap-3 border-b border-neutral-300 bg-neutral-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <div class="min-w-0 flex-1">
                <slot name="toolbar" />
            </div>
            <p v-if="countLabel" class="shrink-0 text-sm text-neutral-600">
                {{ countLabel }}
            </p>
        </div>

        <div>
            <slot />
        </div>

        <div
            v-if="$slots.footer"
            class="border-t border-neutral-300 bg-neutral-50 px-4 py-3"
        >
            <slot name="footer" />
        </div>
    </section>
</template>
