export interface LoginRequest {
    email: string;
    password: string;
}

export interface Me {
    id: number;
    name: string;
    email: string;
    profile?: {
        name: string;
    };
}

export interface AuthResponse {
    token: string;
    user: Me;
}

export interface ApiResponse<T> {
    success: boolean;
    message: string;
    data: T;
}
