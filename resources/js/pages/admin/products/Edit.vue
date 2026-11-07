<script setup lang="ts">
import { Form, Head, Link, setLayoutProps, useHttp } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
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
import { edit, index } from '@/routes/admin/products';
import { show as productShow } from '@/routes/products';
import type { CategoryOption, SubcategoryOption } from '@/types/ecommerce';

const props = defineProps<{
    product: {
        id: number;
        name: string;
        description: string | null;
        old_price: string | number;
        new_price: string | number;
        category_id: number | null;
        subcategory_id: number;
        image_url: string;
        slug?: string;
    };
    categories: CategoryOption[];
}>();

setLayoutProps({
    breadcrumbs: [
        { title: 'Products', href: index() },
        { title: 'Edit', href: edit(props.product.id) },
    ],
});

const categoryId = ref(props.product.category_id ? String(props.product.category_id) : '');
const subcategoryId = ref(String(props.product.subcategory_id));
const subcategories = ref<SubcategoryOption[]>([]);
const previewUrl = ref<string | null>(props.product.image_url);
const loadingSubcategories = ref(false);
const http = useHttp();
const skipReset = ref(true);

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

async function loadSubcategories(id: string): Promise<void> {
    if (!id) {
        subcategories.value = [];
        return;
    }

    loadingSubcategories.value = true;

    try {
        const response = (await http.submit(
            CategorySubcategoryController.get(Number(id)),
        )) as { data: SubcategoryOption[] };

        subcategories.value = response.data ?? [];
    } catch {
        subcategories.value = [];
    } finally {
        loadingSubcategories.value = false;
    }
}

onMounted(async () => {
    await loadSubcategories(categoryId.value);
    skipReset.value = false;
});

watch(categoryId, async (value) => {
    if (skipReset.value) {
        return;
    }

    subcategoryId.value = '';
    await loadSubcategories(value);
});

function onImageChange(event: Event): void {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];

    if (file) {
        previewUrl.value = URL.createObjectURL(file);
        return;
    }

    previewUrl.value = props.product.image_url;
}
</script>

<template>
    <Head title="Edit product" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <PageHeader
            title="Edit product"
            description="Update classification, pricing, and image without losing the current file unless you replace it."
        >
            <template #actions>
                <Button variant="outline" class="rounded-none border-neutral-400 bg-white" as-child>
                    <Link :href="index()">Back to list</Link>
                </Button>
                <Button
                    v-if="product.slug"
                    variant="ghost"
                    class="rounded-none"
                    as-child
                >
                    <Link :href="productShow(product.slug)">View public page</Link>
                </Button>
            </template>
        </PageHeader>

        <Form
            v-bind="ProductController.update.form(product.id)"
            class="max-w-2xl space-y-5"
            enctype="multipart/form-data"
            v-slot="{ errors, processing }"
        >
            <section class="space-y-4 border border-neutral-300 bg-white p-5">
                <div>
                    <h2 class="font-medium">Classification</h2>
                    <p class="text-sm text-muted-foreground">
                        Changing category clears the subcategory until you pick again.
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
                            :disabled="subcategories.length === 0 || loadingSubcategories"
                            :placeholder="
                                loadingSubcategories ? 'Loading...' : 'Select a subcategory'
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
                </div>

                <div class="grid gap-2">
                    <Label for="name">Product name</Label>
                    <Input
                        id="name"
                        name="name"
                        required
                        class="h-10 rounded-none border-neutral-400"
                        :default-value="product.name"
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
                        :value="product.description ?? ''"
                    />
                    <InputError :message="errors.description" />
                </div>
            </section>

            <section class="space-y-4 border border-neutral-300 bg-white p-5">
                <div>
                    <h2 class="font-medium">Pricing & image</h2>
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
                            :default-value="product.old_price"
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
                            :default-value="product.new_price"
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
                        class="h-10 rounded-none border-neutral-400 pt-2"
                        @change="onImageChange"
                    />
                    <FieldHint>Leave empty to keep the current image.</FieldHint>
                    <InputError :message="errors.image" />
                    <img
                        v-if="previewUrl"
                        :src="previewUrl"
                        alt="Product preview"
                        class="mt-2 h-40 w-40 border border-neutral-300 object-cover"
                    />
                </div>
            </section>

            <FormActions
                :processing="processing"
                submit-label="Update product"
                :cancel-href="index()"
            />
        </Form>
    </div>
</template>
