import { useAuthStore } from '@/stores/auth/authStore';

export const checkAuthentication = async (auth: ReturnType<typeof useAuthStore>): Promise<boolean> => {
    const isAuthenticated = auth.isAuthenticated ?? !!localStorage.getItem('token');

    if (isAuthenticated && !auth.user) {
        try {
            await auth.fetchUser();
            return true;
        } catch {
            auth.logout?.();
            return false;
        }
    }

    return !!auth.user;
};
