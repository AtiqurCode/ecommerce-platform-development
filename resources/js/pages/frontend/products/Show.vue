<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';
import { computed } from 'vue';
import { discountPercent, formatMoney } from '@/lib/money';
import { index as productsIndex } from '@/routes/products';

const props = defineProps<{
    product: {
        id: number;
        name: string;
        slug: string;
        description: string | null;
        old_price: string | number;
        new_price: string | number;
        image_url: string;
        category: string | null;
        subcategory: string | null;
        meta_description: string;
    };
}>();

const savings = computed(() =>
    discountPercent(props.product.old_price, props.product.new_price),
);
</script>

<template>
    <Head>
        <title>{{ product.name }}</title>
        <meta
            head-key="description"
            name="description"
            :content="product.meta_description"
        />
        <link
            head-key="canonical"
            rel="canonical"
            :href="`/products/${product.slug}`"
        />
    </Head>

    <div class="space-y-8">
        <Link
            :href="productsIndex()"
            class="inline-flex items-center gap-2 text-sm text-neutral-500 hover:text-neutral-900"
        >
            <ArrowLeft class="size-4" />
            All products
        </Link>

        <article class="grid gap-8 border border-neutral-300 lg:grid-cols-2 lg:gap-0">
            <div class="bg-neutral-100">
                <img
                    :src="product.image_url"
                    :alt="product.name"
                    class="aspect-square w-full object-cover"
                />
            </div>

            <div class="flex flex-col justify-center space-y-6 p-6 sm:p-8">
                <div class="space-y-2">
                    <p class="text-sm text-neutral-500">
                        {{ product.category }} / {{ product.subcategory }}
                    </p>
                    <h1 class="text-3xl font-semibold tracking-tight text-neutral-950 sm:text-4xl">
                        {{ product.name }}
                    </h1>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-3xl font-semibold tabular-nums text-neutral-950">
                        {{ formatMoney(product.new_price) }}
                    </span>
                    <span class="text-lg text-neutral-400 line-through tabular-nums">
                        {{ formatMoney(product.old_price) }}
                    </span>
                    <span
                        v-if="savings"
                        class="bg-[#d62828] px-2 py-1 text-xs font-medium text-white"
                    >
                        -{{ savings }}%
                    </span>
                </div>

                <div class="border-t border-neutral-300 pt-5">
                    <h2 class="text-sm font-medium text-neutral-950">Description</h2>
                    <p class="mt-2 max-w-prose text-base leading-7 text-neutral-600">
                        {{ product.description || 'No description provided for this product.' }}
                    </p>
                </div>

                <div>
                    <Link
                        :href="productsIndex()"
                        class="inline-flex items-center border border-neutral-400 bg-white px-4 py-2.5 text-sm font-medium text-neutral-900 hover:bg-neutral-50"
                    >
                        Continue browsing
                    </Link>
                </div>
            </div>
        </article>
    </div>
</template>
