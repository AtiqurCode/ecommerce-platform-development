<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Toaster } from '@/components/ui/sonner';
import { dashboard, home, login } from '@/routes';
import { index as productsIndex } from '@/routes/products';

const page = usePage();
const user = computed(() => page.props.auth.user);
const appName = computed(() => String(page.props.name || 'Catalogue'));
</script>

<template>
    <div class="min-h-screen bg-white text-neutral-900">
        <header class="sticky top-0 z-20 border-b border-neutral-300 bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
                <div class="flex min-w-0 items-center gap-8">
                    <Link :href="home()" class="truncate text-lg font-semibold">
                        {{ appName }}
                    </Link>
                    <nav class="hidden items-center gap-6 text-sm sm:flex">
                        <Link
                            :href="productsIndex()"
                            class="text-neutral-600 underline-offset-4 hover:text-neutral-950 hover:underline"
                        >
                            Products
                        </Link>
                    </nav>
                </div>

                <div class="flex items-center gap-2 text-sm">
                    <Link
                        :href="productsIndex()"
                        class="px-2 py-1.5 text-neutral-600 sm:hidden"
                    >
                        Products
                    </Link>
                    <Link
                        v-if="user"
                        :href="dashboard()"
                        class="border border-neutral-400 px-3 py-1.5 hover:bg-neutral-50"
                    >
                        Admin
                    </Link>
                    <Link
                        v-else
                        :href="login()"
                        class="bg-neutral-950 px-3 py-1.5 text-white hover:bg-neutral-800"
                    >
                        Log in
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
            <slot />
        </main>

        <footer class="border-t border-neutral-300 py-6">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 text-sm text-neutral-500 sm:px-6">
                <p>{{ appName }}</p>
                <Link :href="productsIndex()" class="hover:text-neutral-900">
                    All products
                </Link>
            </div>
        </footer>

        <Toaster />
    </div>
</template>
