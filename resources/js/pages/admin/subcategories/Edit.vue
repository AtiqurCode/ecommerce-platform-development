<script setup lang="ts">
import { Form, Head, Link, setLayoutProps } from '@inertiajs/vue3';
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
import { edit, index } from '@/routes/admin/subcategories';
import type { CategoryOption } from '@/types/ecommerce';

const props = defineProps<{
    subcategory: {
        id: number;
        category_id: number;
        name: string;
        description: string | null;
    };
    categories: CategoryOption[];
}>();

setLayoutProps({
    breadcrumbs: [
        { title: 'Subcategories', href: index() },
        { title: 'Edit', href: edit(props.subcategory.id) },
    ],
});

const categoryId = ref(String(props.subcategory.category_id));

const categoryOptions = computed(() =>
    props.categories.map((category) => ({
        value: String(category.id),
        label: category.name,
    })),
);
</script>

<template>
    <Head title="Edit subcategory" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <PageHeader
            title="Edit subcategory"
            description="Update the name or move it to another parent category."
        >
            <template #actions>
                <Button variant="outline" class="rounded-none border-neutral-400 bg-white" as-child>
                    <Link :href="index()">Back to list</Link>
                </Button>
            </template>
        </PageHeader>

        <Form
            v-bind="SubcategoryController.update.form(subcategory.id)"
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
                <FieldHint>
                    Changing the parent keeps existing products attached to this subcategory.
                </FieldHint>
                <InputError :message="errors.category_id" />
            </div>

            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    name="name"
                    required
                    class="h-10 rounded-none border-neutral-400"
                    :default-value="subcategory.name"
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
                    :value="subcategory.description ?? ''"
                />
                <InputError :message="errors.description" />
            </div>

            <FormActions
                :processing="processing"
                submit-label="Update subcategory"
                :cancel-href="index()"
            />
        </Form>
    </div>
</template>
