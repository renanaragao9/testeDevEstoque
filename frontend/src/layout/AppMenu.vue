<script setup lang="ts">
import { ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';

interface MenuItem {
    label: string;
    icon?: string;
    to?: string;
    url?: string;
    target?: string;
    class?: string;
    items?: MenuItem[];
    separator?: boolean;
}

const model = ref<MenuItem[]>([
    {
        label: 'Home',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/' }]
    },
    {
        label: 'Produtos',
        items: [
            { label: 'Produtos', icon: 'pi pi-fw pi-box', to: '/products' },
            { label: 'Tipos de Produto', icon: 'pi pi-fw pi-tags', to: '/product-types' },
            { label: 'Marcas', icon: 'pi pi-fw pi-bookmark', to: '/marks' },
            { label: 'Especificações', icon: 'pi pi-fw pi-list', to: '/specifications' }
        ]
    },
    {
        label: 'Estoque',
        items: [
            { label: 'Armazéns', icon: 'pi pi-fw pi-building', to: '/warehouses' },
            { label: 'Fornecedores', icon: 'pi pi-fw pi-users', to: '/suppliers' }
        ]
    }
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i" :root="true"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
