<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineProps<{
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    lastPage: number;
}>();
</script>

<template>
    <nav
        v-if="lastPage > 1"
        class="flex flex-wrap items-center gap-2"
        aria-label="Pagination"
    >
        <template v-for="link in links" :key="`${link.label}-${link.url}`">
            <Button
                v-if="link.url"
                variant="outline"
                size="sm"
                :disabled="link.active"
                as-child
            >
                <Link :href="link.url" preserve-scroll>
                    <span v-html="link.label" />
                </Link>
            </Button>
            <Button v-else variant="outline" size="sm" disabled>
                <span v-html="link.label" />
            </Button>
        </template>
    </nav>
</template>
