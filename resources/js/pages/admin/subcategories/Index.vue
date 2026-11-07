<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Boxes, Pencil } from '@lucide/vue';
import { computed, ref, watch } from 'vue';
import SubcategoryController from '@/actions/App/Http/Controllers/Admin/SubcategoryController';
import ConfirmDeleteDialog from '@/components/admin/ConfirmDeleteDialog.vue';
import DataListShell from '@/components/admin/DataListShell.vue';
import EmptyState from '@/components/admin/EmptyState.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import PaginationLinks from '@/components/admin/PaginationLinks.vue';
import SearchInput from '@/components/admin/SearchInput.vue';
import { Button } from '@/components/ui/button';
import { create, edit, index } from '@/routes/admin/subcategories';
import type { Paginated } from '@/types/ecommerce';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Subcategories', href: index() }],
    },
});

const props = defineProps<{
    subcategories: Paginated<{
        id: number;
        name: string;
        slug: string;
        description: string | null;
        products_count: number;
        category: { id: number; name: string } | null;
    }>;
    filters: {
        search: string;
    };
}>();

const search = ref(props.filters.search ?? '');

const countLabel = computed(() => {
    const total = props.subcategories.total;

    return `${total} ${total === 1 ? 'subcategory' : 'subcategories'}`;
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
    <Head title="Subcategories" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <PageHeader
            title="Subcategories"
            description="Nest product groups under a parent category for cleaner browsing."
        >
            <template #actions>
                <Button class="rounded-none bg-neutral-950 hover:bg-neutral-800" as-child>
                    <Link :href="create()">Create subcategory</Link>
                </Button>
            </template>
        </PageHeader>

        <EmptyState
            v-if="subcategories.data.length === 0"
            :title="search ? 'No matching subcategories' : 'No subcategories yet'"
            :description="
                search
                    ? 'Try another search term, or clear search to browse everything.'
                    : 'Create a subcategory under an existing category first.'
            "
        >
            <template #icon>
                <Boxes class="size-5" />
            </template>
            <template #action>
                <Button
                    v-if="!search"
                    class="rounded-none bg-neutral-950 hover:bg-neutral-800"
                    as-child
                >
                    <Link :href="create()">Create subcategory</Link>
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
                    placeholder="Search by name, slug, or category..."
                />
            </template>

            <div class="grid gap-0 md:hidden">
                <article
                    v-for="subcategory in subcategories.data"
                    :key="subcategory.id"
                    class="border-b border-neutral-300 bg-white p-4 last:border-b-0"
                >
                    <h3 class="font-medium text-neutral-950">{{ subcategory.name }}</h3>
                    <p class="mt-1 text-sm text-neutral-600">
                        {{ subcategory.category?.name }} · {{ subcategory.products_count }} products
                    </p>
                    <div class="mt-4 flex items-center gap-2">
                        <Button variant="outline" size="sm" class="rounded-none" as-child>
                            <Link :href="edit(subcategory.id)">
                                <Pencil class="size-3.5" />
                                Edit
                            </Link>
                        </Button>
                        <ConfirmDeleteDialog
                            title="Delete subcategory?"
                            description="You can only delete a subcategory if it has no products."
                            :action="SubcategoryController.destroy.form(subcategory.id)"
                        />
                    </div>
                </article>
            </div>

            <div class="hidden md:block">
                <table class="min-w-full text-sm">
                    <thead class="bg-neutral-950 text-left text-white">
                        <tr>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Category</th>
                            <th class="px-4 py-3 font-medium">Products</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(subcategory, index) in subcategories.data"
                            :key="subcategory.id"
                            class="border-t border-neutral-300"
                            :class="index % 2 === 1 ? 'bg-neutral-50' : 'bg-white'"
                        >
                            <td class="px-4 py-3">
                                <div class="font-medium text-neutral-950">{{ subcategory.name }}</div>
                                <div class="text-neutral-600">{{ subcategory.slug }}</div>
                            </td>
                            <td class="px-4 py-3 text-neutral-800">
                                {{ subcategory.category?.name }}
                            </td>
                            <td class="px-4 py-3 tabular-nums text-neutral-900">
                                {{ subcategory.products_count }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <Button variant="outline" size="sm" class="rounded-none" as-child>
                                        <Link :href="edit(subcategory.id)">
                                            <Pencil class="size-3.5" />
                                            Edit
                                        </Link>
                                    </Button>
                                    <ConfirmDeleteDialog
                                        title="Delete subcategory?"
                                        description="You can only delete a subcategory if it has no products."
                                        :action="SubcategoryController.destroy.form(subcategory.id)"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <template v-if="subcategories.last_page > 1" #footer>
                <PaginationLinks
                    :links="subcategories.links"
                    :last-page="subcategories.last_page"
                />
            </template>
        </DataListShell>
    </div>
</template>
