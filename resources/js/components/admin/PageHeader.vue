<script setup lang="ts">
import Heading from '@/components/Heading.vue';

defineProps<{
    title: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <Heading :title="title" :description="description" />
        <div v-if="$slots.actions" class="flex flex-wrap items-center gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>
