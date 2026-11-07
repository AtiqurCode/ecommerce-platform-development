<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import SubcategoryController from '@/actions/App/Http/Controllers/Admin/SubcategoryController';
import AdminSelect from '@/components/admin/AdminSelect.vue';
import FieldHint from '@/components/admin/FieldHint.vue';
import FormActions from '@/components/admin/FormActions.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { create, index } from '@/routes/admin/subcategories';
import type { CategoryOption } from '@/types/ecommerce';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Subcategories', href: index() },
            { title: 'Create', href: create() },
        ],
    },
});

const props = defineProps<{
    categories: CategoryOption[];
}>();

const categoryId = ref('');

const categoryOptions = computed(() =>
    props.categories.map((category) => ({
        value: String(category.id),
        label: category.name,
    })),
);
</script>

<template>
    <Head title="Create subcategory" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <PageHeader
            title="Create subcategory"
            description="Pick a parent category, then name the subcategory."
        >
            <template #actions>
                <Button variant="outline" class="rounded-none border-neutral-400 bg-white" as-child>
                    <Link :href="index()">Back to list</Link>
                </Button>
            </template>
        </PageHeader>

        <Form
            v-bind="SubcategoryController.store.form()"
            class="max-w-xl space-y-6 border border-neutral-300 bg-white p-5 sm:p-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="category_id">Parent category</Label>
                <AdminSelect
                    v-model="categoryId"
                    name="category_id"
                    required
                    placeholder="Select a category"
                    :options="categoryOptions"
                />
                <FieldHint>Products will inherit this category through the subcategory.</FieldHint>
                <InputError :message="errors.category_id" />
            </div>

            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    name="name"
                    required
                    placeholder="Mobile Phones"
                    class="h-10 rounded-none border-neutral-400"
                    autofocus
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="description">Description</Label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="border-input placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 flex w-full border border-neutral-300 bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                />
                <InputError :message="errors.description" />
            </div>

            <FormActions
                :processing="processing"
                submit-label="Save subcategory"
                :cancel-href="index()"
            />
        </Form>
    </div>
</template>
