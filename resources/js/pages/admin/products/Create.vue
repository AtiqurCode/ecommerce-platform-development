<script setup lang="ts">
import { Form, Head, Link, useHttp } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import CategorySubcategoryController from '@/actions/App/Http/Controllers/Admin/CategorySubcategoryController';
import ProductController from '@/actions/App/Http/Controllers/Admin/ProductController';
import AdminSelect from '@/components/admin/AdminSelect.vue';
import FieldHint from '@/components/admin/FieldHint.vue';
import FormActions from '@/components/admin/FormActions.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { create, index } from '@/routes/admin/products';
import type { CategoryOption, SubcategoryOption } from '@/types/ecommerce';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Products', href: index() },
            { title: 'Create', href: create() },
        ],
    },
});

const props = defineProps<{
    categories: CategoryOption[];
}>();

const categoryId = ref('');
const subcategoryId = ref('');
const subcategories = ref<SubcategoryOption[]>([]);
const previewUrl = ref<string | null>(null);
const loadingSubcategories = ref(false);
const http = useHttp();

const categoryOptions = computed(() =>
    props.categories.map((category) => ({
        value: String(category.id),
        label: category.name,
    })),
);

const subcategoryOptions = computed(() =>
    subcategories.value.map((subcategory) => ({
        value: String(subcategory.id),
        label: subcategory.name,
    })),
);

watch(categoryId, async (value) => {
    subcategoryId.value = '';
    subcategories.value = [];

    if (!value) {
        return;
    }

    loadingSubcategories.value = true;

    try {
        const response = (await http.submit(
            CategorySubcategoryController.get(Number(value)),
        )) as { data: SubcategoryOption[] };

        subcategories.value = response.data ?? [];
    } catch {
        subcategories.value = [];
    } finally {
        loadingSubcategories.value = false;
    }
});

function onImageChange(event: Event): void {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];

    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }

    previewUrl.value = file ? URL.createObjectURL(file) : null;
}
</script>

<template>
    <Head title="Create product" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <PageHeader
            title="Create product"
            description="Choose category → subcategory, then add pricing and an image."
        >
            <template #actions>
                <Button variant="outline" class="rounded-none border-neutral-400 bg-white" as-child>
                    <Link :href="index()">Back to list</Link>
                </Button>
            </template>
        </PageHeader>

        <Form
            v-bind="ProductController.store.form()"
            class="max-w-2xl space-y-5"
            enctype="multipart/form-data"
            v-slot="{ errors, processing }"
        >
            <section class="space-y-4 border border-neutral-300 bg-white p-5">
                <div>
                    <h2 class="font-medium">Classification</h2>
                    <p class="text-sm text-muted-foreground">
                        Subcategories load after you pick a category.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label>Category</Label>
                        <AdminSelect
                            v-model="categoryId"
                            name="category_id"
                            required
                            placeholder="Select a category"
                            :options="categoryOptions"
                        />
                        <InputError :message="errors.category_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label>Subcategory</Label>
                        <AdminSelect
                            v-model="subcategoryId"
                            name="subcategory_id"
                            required
                            :disabled="!categoryId || loadingSubcategories"
                            :placeholder="
                                loadingSubcategories
                                    ? 'Loading...'
                                    : categoryId
                                      ? 'Select a subcategory'
                                      : 'Select a category first'
                            "
                            :options="subcategoryOptions"
                        />
                        <InputError :message="errors.subcategory_id" />
                    </div>
                </div>
            </section>

            <section class="space-y-4 border border-neutral-300 bg-white p-5">
                <div>
                    <h2 class="font-medium">Product details</h2>
                    <p class="text-sm text-muted-foreground">
                        The public URL slug is generated from the product name.
                    </p>
                </div>

                <div class="grid gap-2">
                    <Label for="name">Product name</Label>
                    <Input
                        id="name"
                        name="name"
                        required
                        class="h-10 rounded-none border-neutral-400"
                        placeholder="iPhone 17 Pro"
                        autofocus
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="description">Description</Label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        class="border-input placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 flex w-full border border-neutral-300 bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                        placeholder="What should shoppers know?"
                    />
                    <InputError :message="errors.description" />
                </div>
            </section>

            <section class="space-y-4 border border-neutral-300 bg-white p-5">
                <div>
                    <h2 class="font-medium">Pricing & image</h2>
                    <p class="text-sm text-muted-foreground">
                        New price must be less than or equal to old price.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="old_price">Old price</Label>
                        <Input
                            id="old_price"
                            name="old_price"
                            type="number"
                            min="0"
                            step="0.01"
                            required
                            class="h-10 rounded-none border-neutral-400"
                            placeholder="1499.00"
                        />
                        <InputError :message="errors.old_price" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="new_price">New price</Label>
                        <Input
                            id="new_price"
                            name="new_price"
                            type="number"
                            min="0"
                            step="0.01"
                            required
                            class="h-10 rounded-none border-neutral-400"
                            placeholder="1299.00"
                        />
                        <InputError :message="errors.new_price" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="image">Product image</Label>
                    <Input
                        id="image"
                        name="image"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        required
                        class="h-10 rounded-none border-neutral-400 pt-2"
                        @change="onImageChange"
                    />
                    <FieldHint>JPEG, PNG, or WebP up to 2MB.</FieldHint>
                    <InputError :message="errors.image" />
                    <img
                        v-if="previewUrl"
                        :src="previewUrl"
                        alt="Selected product preview"
                        class="mt-2 h-40 w-40 border border-neutral-300 object-cover"
                    />
                </div>
            </section>

            <FormActions
                :processing="processing"
                submit-label="Save product"
                :cancel-href="index()"
            />
        </Form>
    </div>
</template>
