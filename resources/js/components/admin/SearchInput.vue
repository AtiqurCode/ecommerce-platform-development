<script setup lang="ts">
import { useDebounceFn } from '@vueuse/core';
import { Search, X } from '@lucide/vue';
import { ref, watch } from 'vue';
import { Input } from '@/components/ui/input';

const props = defineProps<{
    modelValue: string;
    placeholder?: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const localValue = ref(props.modelValue ?? '');

watch(
    () => props.modelValue,
    (value) => {
        if (value !== localValue.value) {
            localValue.value = value ?? '';
        }
    },
);

const emitDebounced = useDebounceFn((value: string) => {
    emit('update:modelValue', value);
}, 300);

function onInput(value: string | number): void {
    const next = String(value);
    localValue.value = next;
    emitDebounced(next);
}

function clear(): void {
    localValue.value = '';
    emit('update:modelValue', '');
}
</script>

<template>
    <div class="relative max-w-md">
        <Search
            class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-neutral-500"
        />
        <Input
            :model-value="localValue"
            type="search"
            class="rounded-none border-neutral-400 bg-white pr-9 pl-9"
            :placeholder="placeholder ?? 'Search...'"
            @update:model-value="onInput"
        />
        <button
            v-if="localValue"
            type="button"
            class="absolute top-1/2 right-2 inline-flex size-7 -translate-y-1/2 items-center justify-center text-neutral-500 hover:bg-neutral-200 hover:text-neutral-900"
            aria-label="Clear search"
            @click="clear"
        >
            <X class="size-4" />
        </button>
    </div>
</template>
