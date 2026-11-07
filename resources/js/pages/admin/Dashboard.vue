<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, Boxes, Layers3, Package, Plus } from '@lucide/vue';
import EmptyState from '@/components/admin/EmptyState.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { formatMoney } from '@/lib/money';
import { dashboard } from '@/routes';
import { create as createCategory, index as categoriesIndex } from '@/routes/admin/categories';
import {
    create as createProduct,
    edit as editProduct,
    index as productsIndex,
} from '@/routes/admin/products';
import { create as createSubcategory, index as subcategoriesIndex } from '@/routes/admin/subcategories';
import { index as storefrontProducts } from '@/routes/products';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

defineProps<{
    stats: {
        categories: number;
        subcategories: number;
        products: number;
    };
    recentProducts: Array<{
        id: number;
        name: string;
        slug: string;
        new_price: string | number;
        image_url: string;
        category: string | null;
        subcategory: string | null;
        created_at: string | null;
    }>;
}>();
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-8 p-4 md:p-6">
        <PageHeader
            title="Store overview"
            description="Jump into catalogue management or open the public storefront."
        >
            <template #actions>
                <Button variant="outline" as-child>
                    <Link :href="storefrontProducts()">View storefront</Link>
                </Button>
                <Button as-child>
                    <Link :href="createProduct()">
                        <Plus class="size-4" />
                        Add product
                    </Link>
                </Button>
            </template>
        </PageHeader>

        <div class="grid gap-4 md:grid-cols-3">
            <Link
                :href="categoriesIndex()"
                class="group border border-neutral-300 bg-white p-5 transition hover:bg-neutral-50"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-muted">
                        <Layers3 class="size-4 text-muted-foreground" />
                    </div>
                    <ArrowRight class="size-4 text-muted-foreground opacity-0 transition group-hover:opacity-100" />
                </div>
                <p class="text-3xl font-semibold tracking-tight">{{ stats.categories }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Categories</p>
            </Link>

            <Link
                :href="subcategoriesIndex()"
                class="group border border-neutral-300 bg-white p-5 transition hover:bg-neutral-50"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-muted">
                        <Boxes class="size-4 text-muted-foreground" />
                    </div>
                    <ArrowRight class="size-4 text-muted-foreground opacity-0 transition group-hover:opacity-100" />
                </div>
                <p class="text-3xl font-semibold tracking-tight">{{ stats.subcategories }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Subcategories</p>
            </Link>

            <Link
                :href="productsIndex()"
                class="group border border-neutral-300 bg-white p-5 transition hover:bg-neutral-50"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-muted">
                        <Package class="size-4 text-muted-foreground" />
                    </div>
                    <ArrowRight class="size-4 text-muted-foreground opacity-0 transition group-hover:opacity-100" />
                </div>
                <p class="text-3xl font-semibold tracking-tight">{{ stats.products }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Products</p>
            </Link>
        </div>

        <section class="grid gap-3 sm:grid-cols-3">
            <Link
                :href="createCategory()"
                class="border border-dashed border-neutral-400 px-4 py-3 text-sm transition hover:bg-white"
            >
                <span class="font-medium">New category</span>
                <span class="mt-1 block text-muted-foreground">Start the catalogue structure</span>
            </Link>
            <Link
                :href="createSubcategory()"
                class="border border-dashed border-neutral-400 px-4 py-3 text-sm transition hover:bg-white"
            >
                <span class="font-medium">New subcategory</span>
                <span class="mt-1 block text-muted-foreground">Nest under an existing category</span>
            </Link>
            <Link
                :href="createProduct()"
                class="border border-dashed border-neutral-400 px-4 py-3 text-sm transition hover:bg-white"
            >
                <span class="font-medium">New product</span>
                <span class="mt-1 block text-muted-foreground">Upload image and set prices</span>
            </Link>
        </section>

        <section class="overflow-hidden border border-neutral-300 bg-white">
            <div class="flex items-center justify-between border-b border-neutral-300 px-5 py-4">
                <div>
                    <h2 class="font-medium">Recent products</h2>
                    <p class="text-sm text-muted-foreground">Latest items added to the catalogue</p>
                </div>
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="productsIndex()">View all</Link>
                </Button>
            </div>

            <EmptyState
                v-if="recentProducts.length === 0"
                class="rounded-none border-0"
                title="No products yet"
                description="Create your first product to populate the storefront and this feed."
            >
                <template #icon>
                    <Package class="size-5" />
                </template>
                <template #action>
                    <Button as-child>
                        <Link :href="createProduct()">Create product</Link>
                    </Button>
                </template>
            </EmptyState>

            <ul v-else class="divide-y divide-neutral-300">
                <li
                    v-for="product in recentProducts"
                    :key="product.id"
                    class="flex items-center gap-4 px-5 py-3.5 transition hover:bg-muted/20"
                >
                    <img
                        :src="product.image_url"
                        :alt="product.name"
                        class="size-12 rounded-lg object-cover"
                    />
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium">{{ product.name }}</p>
                        <p class="truncate text-sm text-muted-foreground">
                            {{ product.category }} · {{ product.subcategory }}
                        </p>
                    </div>
                    <div class="hidden text-right sm:block">
                        <p class="font-medium tabular-nums">{{ formatMoney(product.new_price) }}</p>
                        <p class="text-xs text-muted-foreground">{{ product.created_at }}</p>
                    </div>
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="editProduct(product.id)">Edit</Link>
                    </Button>
                </li>
            </ul>
        </section>
    </div>
</template>
