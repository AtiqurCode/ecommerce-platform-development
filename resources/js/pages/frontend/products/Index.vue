<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PaginationLinks from '@/components/admin/PaginationLinks.vue';
import ProductCard from '@/components/products/ProductCard.vue';
import type { Paginated } from '@/types/ecommerce';

defineProps<{
    groups: Paginated<{
        id: number;
        name: string;
        category: string | null;
        products: Array<{
            id: number;
            name: string;
            slug: string;
            old_price: string | number;
            new_price: string | number;
            image_url: string;
        }>;
    }>;
}>();
</script>

<template>
    <Head>
        <title>Products</title>
        <meta
            head-key="description"
            name="description"
            content="Browse products organized by subcategory across electronics, fashion, and home living."
        />
        <link head-key="canonical" rel="canonical" href="/products" />
    </Head>

    <div class="space-y-10">
        <header class="max-w-2xl space-y-2 border-b border-neutral-300 pb-6">
            <h1 class="text-3xl font-semibold tracking-tight text-neutral-950 sm:text-4xl">
                Products
            </h1>
            <p class="text-base leading-7 text-neutral-600">
                Grouped by subcategory. Open any item for full details.
            </p>
        </header>

        <div
            v-if="groups.data.length === 0"
            class="border border-dashed border-neutral-400 px-6 py-16 text-center"
        >
            <h2 class="text-lg font-medium">Nothing listed yet</h2>
            <p class="mt-1 text-sm text-neutral-500">
                Products will appear here once they are added in admin.
            </p>
        </div>

        <section
            v-for="group in groups.data"
            :key="group.id"
            class="space-y-4"
        >
            <div class="flex flex-wrap items-end justify-between gap-3 border-b border-neutral-300 pb-2">
                <div>
                    <p class="text-sm text-neutral-500">{{ group.category }}</p>
                    <h2 class="text-xl font-semibold tracking-tight">{{ group.name }}</h2>
                </div>
                <p class="text-sm text-neutral-500">
                    {{ group.products.length }}
                    {{ group.products.length === 1 ? 'item' : 'items' }}
                </p>
            </div>

            <div class="grid gap-px bg-neutral-300 sm:grid-cols-2 lg:grid-cols-3">
                <ProductCard
                    v-for="product in group.products"
                    :key="product.id"
                    :product="product"
                />
            </div>
        </section>

        <PaginationLinks :links="groups.links" :last-page="groups.last_page" />
    </div>
</template>
