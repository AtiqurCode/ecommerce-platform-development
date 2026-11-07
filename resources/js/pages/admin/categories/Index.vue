<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Layers3, Pencil } from '@lucide/vue';
import { computed, ref, watch } from 'vue';
import CategoryController from '@/actions/App/Http/Controllers/Admin/CategoryController';
import ConfirmDeleteDialog from '@/components/admin/ConfirmDeleteDialog.vue';
import DataListShell from '@/components/admin/DataListShell.vue';
import EmptyState from '@/components/admin/EmptyState.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import PaginationLinks from '@/components/admin/PaginationLinks.vue';
import SearchInput from '@/components/admin/SearchInput.vue';
import { Button } from '@/components/ui/button';
import { create, edit, index } from '@/routes/admin/categories';
import type { Paginated } from '@/types/ecommerce';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Categories', href: index() }],
    },
});

const props = defineProps<{
    categories: Paginated<{
        id: number;
        name: string;
        slug: string;
        description: string | null;
        subcategories_count: number;
    }>;
    filters: {
        search: string;
    };
}>();

const search = ref(props.filters.search ?? '');

const countLabel = computed(() => {
    const total = props.categories.total;

    return `${total} ${total === 1 ? 'category' : 'categories'}`;
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
    <Head title="Categories" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <PageHeader
            title="Categories"
            description="Top-level groups for your catalogue. Add these before subcategories."
        >
            <template #actions>
                <Button class="rounded-none bg-neutral-950 hover:bg-neutral-800" as-child>
                    <Link :href="create()">Create category</Link>
                </Button>
            </template>
        </PageHeader>

        <EmptyState
            v-if="categories.data.length === 0"
            :title="search ? 'No matching categories' : 'No categories yet'"
            :description="
                search
                    ? 'Try another search term, or clear the search to see everything.'
                    : 'Create your first category to start organizing products.'
            "
        >
            <template #icon>
                <Layers3 class="size-5" />
            </template>
            <template #action>
                <Button
                    v-if="!search"
                    class="rounded-none bg-neutral-950 hover:bg-neutral-800"
                    as-child
                >
                    <Link :href="create()">Create category</Link>
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
                <SearchInput v-model="search" placeholder="Search by name or slug..." />
            </template>

            <div class="grid gap-0 md:hidden">
                <article
                    v-for="category in categories.data"
                    :key="category.id"
                    class="border-b border-neutral-300 bg-white p-4 last:border-b-0"
                >
                    <h3 class="font-medium text-neutral-950">{{ category.name }}</h3>
                    <p class="mt-1 text-sm text-neutral-600">
                        {{ category.description || 'No description' }}
                    </p>
                    <p class="mt-2 text-xs text-neutral-500">
                        {{ category.subcategories_count }} subcategories · {{ category.slug }}
                    </p>
                    <div class="mt-4 flex items-center gap-2">
                        <Button variant="outline" size="sm" class="rounded-none" as-child>
                            <Link :href="edit(category.id)">
                                <Pencil class="size-3.5" />
                                Edit
                            </Link>
                        </Button>
                        <ConfirmDeleteDialog
                            title="Delete category?"
                            description="You can only delete a category if it has no subcategories."
                            :action="CategoryController.destroy.form(category.id)"
                        />
                    </div>
                </article>
            </div>

            <div class="hidden md:block">
                <table class="min-w-full text-sm">
                    <thead class="bg-neutral-950 text-left text-white">
                        <tr>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Slug</th>
                            <th class="px-4 py-3 font-medium">Subcategories</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr
                            v-for="(category, index) in categories.data"
                            :key="category.id"
                            class="border-t border-neutral-300"
                            :class="index % 2 === 1 ? 'bg-neutral-50' : 'bg-white'"
                        >
                            <td class="px-4 py-3">
                                <div class="font-medium text-neutral-950">{{ category.name }}</div>
                                <div class="text-neutral-600">
                                    {{ category.description || 'No description' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-neutral-600">{{ category.slug }}</td>
                            <td class="px-4 py-3 tabular-nums text-neutral-900">
                                {{ category.subcategories_count }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <Button variant="outline" size="sm" class="rounded-none" as-child>
                                        <Link :href="edit(category.id)">
                                            <Pencil class="size-3.5" />
                                            Edit
                                        </Link>
                                    </Button>
                                    <ConfirmDeleteDialog
                                        title="Delete category?"
                                        description="You can only delete a category if it has no subcategories."
                                        :action="CategoryController.destroy.form(category.id)"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <template v-if="categories.last_page > 1" #footer>
                <PaginationLinks :links="categories.links" :last-page="categories.last_page" />
            </template>
        </DataListShell>
    </div>
</template>
