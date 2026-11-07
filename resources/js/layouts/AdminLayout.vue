<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    Boxes,
    Layers3,
    LayoutDashboard,
    LogOut,
    Menu,
    Package,
    Settings,
    Store,
    X,
} from '@lucide/vue';
import { computed, ref, watch } from 'vue';
import { Toaster } from '@/components/ui/sonner';
import { dashboard, logout } from '@/routes';
import { index as categoriesIndex } from '@/routes/admin/categories';
import { index as productsIndex } from '@/routes/admin/products';
import { index as subcategoriesIndex } from '@/routes/admin/subcategories';
import { edit as profileEdit } from '@/routes/profile';
import { index as storefrontProducts } from '@/routes/products';
import type { BreadcrumbItem } from '@/types';

const props = withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = computed(() => page.props.auth.user);
const appName = computed(() => String(page.props.name || 'Catalogue'));
const mobileOpen = ref(false);
const currentUrl = computed(() => page.url);

watch(
    () => page.url,
    () => {
        mobileOpen.value = false;
    },
);

const navItems = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutDashboard,
        match: '/dashboard',
    },
    {
        title: 'Categories',
        href: categoriesIndex(),
        icon: Layers3,
        match: '/admin/categories',
    },
    {
        title: 'Subcategories',
        href: subcategoriesIndex(),
        icon: Boxes,
        match: '/admin/subcategories',
    },
    {
        title: 'Products',
        href: productsIndex(),
        icon: Package,
        match: '/admin/products',
    },
];

function isActive(match: string): boolean {
    return currentUrl.value === match || currentUrl.value.startsWith(`${match}/`);
}
</script>

<template>
    <div class="min-h-screen bg-[#f3f3f1] text-neutral-900">
        <div class="lg:grid lg:min-h-screen lg:grid-cols-[248px_minmax(0,1fr)]">
            <aside class="hidden flex-col border-r border-neutral-800 bg-neutral-950 text-neutral-100 lg:flex">
                <div class="border-b border-neutral-800 px-5 py-5">
                    <Link :href="dashboard()" class="block">
                        <p class="text-lg font-semibold tracking-tight text-white">
                            {{ appName }}
                        </p>
                        <p class="mt-0.5 text-sm text-neutral-400">Admin</p>
                    </Link>
                </div>

                <nav class="flex-1 space-y-1 px-2 py-4">
                    <Link
                        v-for="item in navItems"
                        :key="item.title"
                        :href="item.href"
                        class="flex items-center gap-3 border-l-2 px-3 py-2.5 text-sm transition"
                        :class="
                            isActive(item.match)
                                ? 'border-[#d62828] bg-neutral-900 font-medium text-white'
                                : 'border-transparent text-neutral-400 hover:bg-neutral-900 hover:text-neutral-100'
                        "
                    >
                        <component :is="item.icon" class="size-4 shrink-0" />
                        <span>{{ item.title }}</span>
                    </Link>
                </nav>

                <div class="space-y-2 border-t border-neutral-800 p-3">
                    <Link
                        :href="storefrontProducts()"
                        class="flex items-center gap-2 px-3 py-2 text-sm text-neutral-300 hover:bg-neutral-900 hover:text-white"
                    >
                        <Store class="size-4" />
                        Open storefront
                    </Link>

                    <div class="border border-neutral-800 px-3 py-3">
                        <p class="truncate text-sm font-medium text-white">
                            {{ user?.name }}
                        </p>
                        <p class="truncate text-xs text-neutral-500">
                            {{ user?.email }}
                        </p>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <Link
                                :href="profileEdit()"
                                class="inline-flex items-center justify-center gap-1.5 border border-neutral-700 px-2 py-1.5 text-xs text-neutral-200 hover:bg-neutral-900"
                            >
                                <Settings class="size-3.5" />
                                Settings
                            </Link>
                            <Link
                                :href="logout()"
                                method="post"
                                as="button"
                                class="inline-flex items-center justify-center gap-1.5 border border-neutral-700 px-2 py-1.5 text-xs text-neutral-200 hover:bg-neutral-900"
                            >
                                <LogOut class="size-3.5" />
                                Log out
                            </Link>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="min-w-0">
                <header class="sticky top-0 z-30 border-b border-neutral-300 bg-[#f3f3f1]/95 backdrop-blur">
                    <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6">
                        <div class="flex min-w-0 items-center gap-3">
                            <button
                                type="button"
                                class="inline-flex size-10 items-center justify-center border border-neutral-400 bg-white text-neutral-800 lg:hidden"
                                aria-label="Open menu"
                                @click="mobileOpen = true"
                            >
                                <Menu class="size-5" />
                            </button>

                            <nav
                                v-if="props.breadcrumbs.length"
                                class="hidden min-w-0 items-center gap-2 text-sm text-neutral-500 sm:flex"
                                aria-label="Breadcrumb"
                            >
                                <template
                                    v-for="(crumb, index) in props.breadcrumbs"
                                    :key="`${crumb.title}-${index}`"
                                >
                                    <span v-if="index > 0" class="text-neutral-300">/</span>
                                    <Link
                                        v-if="index < props.breadcrumbs.length - 1"
                                        :href="crumb.href"
                                        class="truncate hover:text-neutral-900"
                                    >
                                        {{ crumb.title }}
                                    </Link>
                                    <span
                                        v-else
                                        class="truncate font-medium text-neutral-900"
                                    >
                                        {{ crumb.title }}
                                    </span>
                                </template>
                            </nav>
                        </div>

                        <Link
                            :href="storefrontProducts()"
                            class="hidden items-center gap-2 border border-neutral-400 bg-white px-3 py-2 text-sm text-neutral-800 hover:bg-neutral-50 sm:inline-flex"
                        >
                            <Store class="size-4" />
                            Storefront
                        </Link>
                    </div>
                </header>

                <div class="min-h-[calc(100vh-3.5rem)]">
                    <slot />
                </div>
            </div>
        </div>

        <div
            v-if="mobileOpen"
            class="fixed inset-0 z-40 bg-black/40 lg:hidden"
            @click="mobileOpen = false"
        />

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-[280px] max-w-[86vw] flex-col bg-neutral-950 text-neutral-100 transition-transform duration-200 lg:hidden"
            :class="mobileOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex items-center justify-between border-b border-neutral-800 px-4 py-4">
                <div>
                    <p class="text-base font-semibold text-white">{{ appName }}</p>
                    <p class="text-sm text-neutral-400">Admin</p>
                </div>
                <button
                    type="button"
                    class="inline-flex size-9 items-center justify-center border border-neutral-700"
                    aria-label="Close menu"
                    @click="mobileOpen = false"
                >
                    <X class="size-4" />
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-2 py-4">
                <Link
                    v-for="item in navItems"
                    :key="item.title"
                    :href="item.href"
                    class="flex items-center gap-3 border-l-2 px-3 py-2.5 text-sm"
                    :class="
                        isActive(item.match)
                            ? 'border-[#d62828] bg-neutral-900 text-white'
                            : 'border-transparent text-neutral-400'
                    "
                >
                    <component :is="item.icon" class="size-4 shrink-0" />
                    <span>{{ item.title }}</span>
                </Link>
            </nav>

            <div class="space-y-1 border-t border-neutral-800 p-3">
                <Link
                    :href="storefrontProducts()"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-neutral-300"
                >
                    <Store class="size-4" />
                    Open storefront
                </Link>
                <Link
                    :href="profileEdit()"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-neutral-300"
                >
                    <Settings class="size-4" />
                    Settings
                </Link>
                <Link
                    :href="logout()"
                    method="post"
                    as="button"
                    class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm text-neutral-300"
                >
                    <LogOut class="size-4" />
                    Log out
                </Link>
            </div>
        </aside>

        <Toaster />
    </div>
</template>
