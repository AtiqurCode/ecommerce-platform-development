<script setup lang="ts">
import { Form, Head, Link, setLayoutProps } from '@inertiajs/vue3';
import CategoryController from '@/actions/App/Http/Controllers/Admin/CategoryController';
import FieldHint from '@/components/admin/FieldHint.vue';
import FormActions from '@/components/admin/FormActions.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { edit, index } from '@/routes/admin/categories';

const props = defineProps<{
    category: {
        id: number;
        name: string;
        description: string | null;
    };
}>();

setLayoutProps({
    breadcrumbs: [
        { title: 'Categories', href: index() },
        { title: 'Edit', href: edit(props.category.id) },
    ],
});
</script>

<template>
    <Head title="Edit category" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <PageHeader
            title="Edit category"
            description="Update the category details. Renaming regenerates the slug."
        >
            <template #actions>
                <Button variant="outline" class="rounded-none border-neutral-400 bg-white" as-child>
                    <Link :href="index()">Back to list</Link>
                </Button>
            </template>
        </PageHeader>

        <Form
            v-bind="CategoryController.update.form(category.id)"
            class="max-w-xl space-y-6 border border-neutral-300 bg-white p-5 sm:p-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    name="name"
                    required
                    :default-value="category.name"
                    class="h-10 rounded-none border-neutral-400 bg-white"
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
                    class="placeholder:text-muted-foreground flex w-full border border-neutral-400 bg-white px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-neutral-900 focus-visible:ring-[3px] focus-visible:ring-neutral-900/20"
                    :value="category.description ?? ''"
                />
                <FieldHint>Leave blank if you do not need a description.</FieldHint>
                <InputError :message="errors.description" />
            </div>

            <FormActions
                :processing="processing"
                submit-label="Update category"
                :cancel-href="index()"
            />
        </Form>
    </div>
</template>
