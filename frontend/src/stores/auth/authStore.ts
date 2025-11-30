import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Me, LoginRequest, AuthResponse, ApiResponse } from '@/types/authType';
import type { ErrorResponse } from '@/types/global/errorResponse';
import { AuthService, setAuthToken, clearAuthToken, AppError } from '@/services/auth/authService';

type ActionResult = { success: boolean; message: string };

const isClient = (): boolean => typeof window !== 'undefined';

const saveToken = (token: string): void => {
    if (isClient()) {
        localStorage.setItem('token', token);
    }
};

const removeToken = (): void => {
    if (isClient()) {
        localStorage.removeItem('token');
    }
};

const getStoredToken = (): string | null => {
    return isClient() ? localStorage.getItem('token') : null;
};

const handleError = (error: unknown): ErrorResponse => {
    if (error instanceof AppError) {
        return (error.data as ErrorResponse) || { message: error.message, errors: {} };
    }
    if (error instanceof Error) {
        return { message: error.message, errors: {} };
    }
    return { message: 'Erro desconhecido', errors: {} };
};

export const useAuthStore = defineStore('auth', () => {
    const user = ref<Me | null>(null);
    const token = ref<string | null>(null);
    const isAuthenticated = computed(() => !!token.value);
    const isLoading = ref(false);
    const error = ref<ErrorResponse | null>(null);

    const login = async (credentials: LoginRequest): Promise<ActionResult> => {
        isLoading.value = true;
        error.value = null;

        try {
            const response: ApiResponse<AuthResponse> = await AuthService.login(credentials);
            token.value = response.data.token;
            user.value = response.data.user;

            saveToken(response.data.token);
            setAuthToken(response.data.token);

            return { success: true, message: response.message };
        } catch (err: unknown) {
            error.value = handleError(err);
            return { success: false, message: error.value.message };
        } finally {
            isLoading.value = false;
        }
    };

    const clearState = (): void => {
        user.value = null;
        token.value = null;
        error.value = null;
        removeToken();
        clearAuthToken();
    };

    const logout = async (): Promise<void> => {
        isLoading.value = true;
        try {
            await AuthService.logout();
        } finally {
            clearState();
            isLoading.value = false;
        }
    };

    const isAuthenticationError = (status?: number): boolean => {
        return status === 401 || status === 419;
    };

    const fetchUser = async (): Promise<void> => {
        if (!token.value) return;

        isLoading.value = true;
        try {
            const response: ApiResponse<Me> = await AuthService.getUser();
            user.value = response.data;
        } catch (err: unknown) {
            if (err instanceof AppError && isAuthenticationError(err.status)) {
                await logout();
                return;
            }
            error.value = handleError(err);
            throw err;
        } finally {
            isLoading.value = false;
        }
    };

    const initializeAuth = async (): Promise<void> => {
        const storedToken = getStoredToken();
        if (storedToken) {
            setAuthToken(storedToken);
            token.value = storedToken;
            try {
                await fetchUser();
            } catch {
                clearState();
            }
        }
    };

    const clearError = (): void => {
        error.value = null;
    };

    return {
        user,
        token,
        isAuthenticated,
        isLoading,
        error,
        login,
        logout,
        fetchUser,
        initializeAuth,
        clearError
    };
});
