<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import CategoryController from '@/actions/App/Http/Controllers/Admin/CategoryController';
import FieldHint from '@/components/admin/FieldHint.vue';
import FormActions from '@/components/admin/FormActions.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { create, index } from '@/routes/admin/categories';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Categories', href: index() },
            { title: 'Create', href: create() },
        ],
    },
});
</script>

<template>
    <Head title="Create category" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <PageHeader
            title="Create category"
            description="Add a top-level category. A unique slug is generated from the name."
        >
            <template #actions>
                <Button variant="outline" class="rounded-none border-neutral-400 bg-white" as-child>
                    <Link :href="index()">Back to list</Link>
                </Button>
            </template>
        </PageHeader>

        <Form
            v-bind="CategoryController.store.form()"
            class="max-w-xl space-y-6 border border-neutral-300 bg-white p-5 sm:p-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    name="name"
                    required
                    placeholder="Electronics"
                    class="h-10 rounded-none border-neutral-400 bg-white"
                    autofocus
                />
                <FieldHint>Shown in admin tables and used to generate the slug.</FieldHint>
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="description">Description</Label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="placeholder:text-muted-foreground flex w-full border border-neutral-400 bg-white px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-neutral-900 focus-visible:ring-[3px] focus-visible:ring-neutral-900/20"
                    placeholder="Optional short summary"
                />
                <FieldHint>Optional. Helps your team remember what belongs here.</FieldHint>
                <InputError :message="errors.description" />
            </div>

            <FormActions
                :processing="processing"
                submit-label="Save category"
                :cancel-href="index()"
            />
        </Form>
    </div>
</template>
