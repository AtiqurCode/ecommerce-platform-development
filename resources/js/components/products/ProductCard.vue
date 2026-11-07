<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { discountPercent, formatMoney } from '@/lib/money';
import { show as productShow } from '@/routes/products';

const props = defineProps<{
    product: {
        name: string;
        slug: string;
        old_price: string | number;
        new_price: string | number;
        image_url: string;
    };
}>();

const savings = computed(() =>
    discountPercent(props.product.old_price, props.product.new_price),
);
</script>

<template>
    <article class="group border border-neutral-300 bg-white">
        <Link :href="productShow(product.slug)" class="block focus-visible:outline-none">
            <div class="relative overflow-hidden bg-neutral-100">
                <img
                    :src="product.image_url"
                    :alt="product.name"
                    class="aspect-square w-full object-cover"
                    loading="lazy"
                />
                <span
                    v-if="savings"
                    class="absolute top-0 left-0 bg-[#d62828] px-2 py-1 text-xs font-medium text-white"
                >
                    -{{ savings }}%
                </span>
            </div>
            <div class="space-y-2 border-t border-neutral-300 p-4">
                <h3 class="line-clamp-2 min-h-12 text-[15px] leading-snug font-medium text-neutral-950">
                    {{ product.name }}
                </h3>
                <div class="flex items-baseline gap-2">
                    <span class="text-base font-semibold tabular-nums text-neutral-950">
                        {{ formatMoney(product.new_price) }}
                    </span>
                    <span class="text-sm text-neutral-400 line-through tabular-nums">
                        {{ formatMoney(product.old_price) }}
                    </span>
                </div>
                <span class="inline-flex text-sm text-neutral-600 group-hover:text-neutral-950 group-hover:underline">
                    Details
                </span>
            </div>
        </Link>
    </article>
</template>
