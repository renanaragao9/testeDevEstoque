import AppLayout from '@/layout/AppLayout.vue';
import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import { useAuthStore } from '@/stores/auth/authStore';
import { checkAuthentication } from '@/utils/checkAuthentication';

declare module 'vue-router' {
    interface RouteMeta {
        requiresAuth?: boolean;
        guestOnly?: boolean;
        allowedProfiles?: string[];
    }
}

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        component: AppLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                name: 'dashboard',
                component: () => import('@/views/Dashboard.vue')
            },
            {
                path: '/product-types',
                name: 'product-types',
                component: () => import('@/views/ProductTypeView.vue')
            },
            {
                path: '/marks',
                name: 'marks',
                component: () => import('@/views/MarkView.vue')
            },
            {
                path: '/specifications',
                name: 'specifications',
                component: () => import('@/views/SpecificationView.vue')
            },
            {
                path: '/products',
                name: 'products',
                component: () => import('@/views/ProductView.vue')
            },
            {
                path: '/warehouses',
                name: 'warehouses',
                component: () => import('@/views/WarehouseView.vue')
            },
            {
                path: '/suppliers',
                name: 'suppliers',
                component: () => import('@/views/SupplierView.vue')
            },
            {
                path: '/purchases',
                name: 'purchases',
                component: () => import('@/views/PurchaseView.vue')
            },
            {
                path: '/customers',
                name: 'customers',
                component: () => import('@/views/CustomerView.vue')
            },
            {
                path: '/pages/empty',
                name: 'empty',
                component: () => import('@/views/pages/Empty.vue')
            }
        ]
    },
    {
        path: '/landing',
        name: 'landing',
        component: () => import('@/views/pages/Landing.vue')
    },
    {
        path: '/notfound',
        name: 'notfound',
        component: () => import('@/views/pages/NotFound.vue')
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('@/views/pages/auth/Login.vue'),
        meta: { guestOnly: true }
    },
    {
        path: '/access',
        name: 'accessDenied',
        component: () => import('@/views/pages/auth/Access.vue')
    },
    {
        path: '/error',
        name: 'error',
        component: () => import('@/views/pages/auth/Error.vue')
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to) => {
    const auth = useAuthStore();
    const authed = await checkAuthentication(auth);

    if (to.matched.some((r) => r.meta.requiresAuth) && !authed) {
        return { name: 'login' };
    }

    if (to.matched.some((r) => r.meta.guestOnly) && authed) {
        return { name: 'dashboard' };
    }

    return true;
});

export default router;
