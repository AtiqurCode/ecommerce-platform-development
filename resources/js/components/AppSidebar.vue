<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Boxes, Layers3, LayoutGrid, Package, Store } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as categoriesIndex } from '@/routes/admin/categories';
import { index as productsIndex } from '@/routes/admin/products';
import { index as subcategoriesIndex } from '@/routes/admin/subcategories';
import { index as storefrontProducts } from '@/routes/products';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Categories',
        href: categoriesIndex(),
        icon: Layers3,
    },
    {
        title: 'Subcategories',
        href: subcategoriesIndex(),
        icon: Boxes,
    },
    {
        title: 'Products',
        href: productsIndex(),
        icon: Package,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Storefront',
        href: storefrontProducts(),
        icon: Store,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
