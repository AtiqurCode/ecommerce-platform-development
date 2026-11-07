<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Package, Pencil } from '@lucide/vue';
import { computed, ref, watch } from 'vue';
import ProductController from '@/actions/App/Http/Controllers/Admin/ProductController';
import ConfirmDeleteDialog from '@/components/admin/ConfirmDeleteDialog.vue';
import DataListShell from '@/components/admin/DataListShell.vue';
import EmptyState from '@/components/admin/EmptyState.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import PaginationLinks from '@/components/admin/PaginationLinks.vue';
import SearchInput from '@/components/admin/SearchInput.vue';
import { Button } from '@/components/ui/button';
import { formatMoney } from '@/lib/money';
import { create, edit, index } from '@/routes/admin/products';
import { show as productShow } from '@/routes/products';
import type { Paginated } from '@/types/ecommerce';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Products', href: index() }],
    },
});

const props = defineProps<{
    products: Paginated<{
        id: number;
        name: string;
        slug: string;
        old_price: string | number;
        new_price: string | number;
        image_url: string;
        category: string | null;
        subcategory: string | null;
    }>;
    filters: {
        search: string;
    };
}>();

const search = ref(props.filters.search ?? '');

const countLabel = computed(() => {
    const total = props.products.total;

    return `${total} ${total === 1 ? 'product' : 'products'}`;
});

watch(search, (value) => {
    router.get(
        index().url,
        { search: value || undefined },
        { preserveState: true, replace: true, preserveScroll: true },
    );
});
</script>

<template>
    <Head title="Products" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <PageHeader
            title="Products"
            description="Manage catalogue items, pricing, and product images."
        >
            <template #actions>
                <Button class="rounded-none bg-neutral-950 hover:bg-neutral-800" as-child>
                    <Link :href="create()">Create product</Link>
                </Button>
            </template>
        </PageHeader>

        <EmptyState
            v-if="products.data.length === 0"
            :title="search ? 'No matching products' : 'No products yet'"
            :description="
                search
                    ? 'Try another search term, or clear search to browse everything.'
                    : 'Add a product with category, subcategory, image, and pricing.'
            "
        >
            <template #icon>
                <Package class="size-5" />
            </template>
            <template #action>
                <Button
                    v-if="!search"
                    class="rounded-none bg-neutral-950 hover:bg-neutral-800"
                    as-child
                >
                    <Link :href="create()">Create product</Link>
                </Button>
                <Button
                    v-else
                    variant="outline"
                    class="rounded-none"
                    @click="search = ''"
                >
                    Clear search
                </Button>
            </template>
        </EmptyState>

        <DataListShell v-else :count-label="countLabel">
            <template #toolbar>
                <SearchInput
                    v-model="search"
                    placeholder="Search by product, category, or subcategory..."
                />
            </template>

            <div class="grid gap-0 md:hidden">
                <article
                    v-for="product in products.data"
                    :key="product.id"
                    class="border-b border-neutral-300 bg-white p-4 last:border-b-0"
                >
                    <div class="flex gap-3">
                        <img
                            :src="product.image_url"
                            :alt="product.name"
                            class="size-16 border border-neutral-300 object-cover"
                        />
                        <div class="min-w-0 flex-1">
                            <h3 class="font-medium text-neutral-950">{{ product.name }}</h3>
                            <p class="mt-1 text-sm text-neutral-600">
                                {{ product.category }} · {{ product.subcategory }}
                            </p>
                            <p class="mt-2 font-medium tabular-nums text-neutral-950">
                                {{ formatMoney(product.new_price) }}
                                <span class="ml-2 text-xs font-normal text-neutral-400 line-through">
                                    {{ formatMoney(product.old_price) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <Button variant="outline" size="sm" class="rounded-none" as-child>
                            <Link :href="edit(product.id)">
                                <Pencil class="size-3.5" />
                                Edit
                            </Link>
                        </Button>
                        <Button variant="ghost" size="sm" class="rounded-none" as-child>
                            <Link :href="productShow(product.slug)">View</Link>
                        </Button>
                        <ConfirmDeleteDialog
                            title="Delete product?"
                            description="This permanently removes the product and its image."
                            :action="ProductController.destroy.form(product.id)"
                        />
                    </div>
                </article>
            </div>

            <div class="hidden md:block">
                <table class="min-w-full text-sm">
                    <thead class="bg-neutral-950 text-left text-white">
                        <tr>
                            <th class="px-4 py-3 font-medium">Product</th>
                            <th class="px-4 py-3 font-medium">Classification</th>
                            <th class="px-4 py-3 font-medium">Price</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(product, index) in products.data"
                            :key="product.id"
                            class="border-t border-neutral-300"
                            :class="index % 2 === 1 ? 'bg-neutral-50' : 'bg-white'"
                        >
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img
                                        :src="product.image_url"
                                        :alt="product.name"
                                        class="size-12 border border-neutral-300 object-cover"
                                    />
                                    <div>
                                        <div class="font-medium text-neutral-950">
                                            {{ product.name }}
                                        </div>
                                        <div class="text-neutral-600">{{ product.slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-neutral-800">
                                {{ product.category }} · {{ product.subcategory }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium tabular-nums text-neutral-950">
                                    {{ formatMoney(product.new_price) }}
                                </div>
                                <div class="text-neutral-400 line-through tabular-nums">
                                    {{ formatMoney(product.old_price) }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <Button variant="outline" size="sm" class="rounded-none" as-child>
                                        <Link :href="edit(product.id)">
                                            <Pencil class="size-3.5" />
                                            Edit
                                        </Link>
                                    </Button>
                                    <Button variant="ghost" size="sm" class="rounded-none" as-child>
                                        <Link :href="productShow(product.slug)">View</Link>
                                    </Button>
                                    <ConfirmDeleteDialog
                                        title="Delete product?"
                                        description="This permanently removes the product and its image."
                                        :action="ProductController.destroy.form(product.id)"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <template v-if="products.last_page > 1" #footer>
                <PaginationLinks :links="products.links" :last-page="products.last_page" />
            </template>
        </DataListShell>
    </div>
</template>
