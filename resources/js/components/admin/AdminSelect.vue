<script setup lang="ts">
import { computed } from 'vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

export type AdminSelectOption = {
    value: string;
    label: string;
};

withDefaults(
    defineProps<{
        name: string;
        options: AdminSelectOption[];
        placeholder?: string;
        disabled?: boolean;
        required?: boolean;
    }>(),
    {
        placeholder: 'Select an option',
        disabled: false,
        required: false,
    },
);

const model = defineModel<string>({ default: '' });

const selectedValue = computed({
    get: () => (model.value === '' ? undefined : model.value),
    set: (value: string | number | bigint | Record<string, any> | null) => {
        model.value = value == null ? '' : String(value);
    },
});
</script>

<template>
    <div class="w-full">
        <input type="hidden" :name="name" :value="model" :required="required" />
        <Select v-model="selectedValue" :disabled="disabled" :required="required">
            <SelectTrigger
                class="h-10 w-full rounded-none border-neutral-400 bg-white px-3 text-left shadow-none hover:bg-neutral-50 focus-visible:ring-neutral-900/20"
            >
                <SelectValue :placeholder="placeholder" />
            </SelectTrigger>
            <SelectContent class="rounded-none border-neutral-300 shadow-md">
                <SelectItem
                    v-for="option in options"
                    :key="option.value"
                    :value="option.value"
                    class="cursor-pointer rounded-none py-2"
                >
                    {{ option.label }}
                </SelectItem>
            </SelectContent>
        </Select>
    </div>
</template>
