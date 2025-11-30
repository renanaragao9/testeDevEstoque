import api from '@/config/api';
import type { LoginRequest, AuthResponse, ApiResponse, Me } from '@/types/authType';

interface AxiosErrorResponse {
    response?: {
        data?: { message?: string };
        status?: number;
    };
    config?: unknown;
}

export const isAxiosError = (error: unknown): error is AxiosErrorResponse => {
    const err = error as AxiosErrorResponse;
    return err?.response !== undefined && err?.config !== undefined;
};

export class AppError extends Error {
    constructor(
        message: string,
        public status?: number,
        public data?: unknown
    ) {
        super(message);
        this.name = 'AppError';
    }
}

export const setAuthToken = (token: string): void => {
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
};

export const clearAuthToken = (): void => {
    delete api.defaults.headers.common['Authorization'];
};

const handleAxiosError = (error: unknown, defaultMessage: string): void => {
    if (isAxiosError(error)) {
        const message = error.response?.data?.message ?? defaultMessage;
        throw new AppError(message, error.response?.status, error.response?.data);
    }
    throw error;
};

export class AuthService {
    static async login(credentials: LoginRequest): Promise<ApiResponse<AuthResponse>> {
        try {
            const { data } = await api.post<ApiResponse<AuthResponse>>('/auth/login', credentials);
            return data;
        } catch (error) {
            handleAxiosError(error, 'Erro ao fazer login');
            throw error;
        }
    }

    static async logout(): Promise<void> {
        try {
            await api.post('/auth/logout');
        } catch (error) {
            handleAxiosError(error, 'Erro ao fazer logout');
        }
    }

    static async getUser(): Promise<ApiResponse<Me>> {
        try {
            const { data } = await api.get<ApiResponse<Me>>('auth/me');
            return data;
        } catch (error) {
            handleAxiosError(error, 'Erro ao buscar usuário');
            throw error;
        }
    }
}
