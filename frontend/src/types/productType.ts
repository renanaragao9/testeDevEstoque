export interface ProductType {
    id: number;
    name: string;
    description?: string;
    createdAt?: string;
}

export interface ProductTypePayload {
    name: string;
    description?: string;
}

export interface ProductTypeResponse {
    data: ProductType[];
    links: {
        first: string | null;
        last: string | null;
        prev: string | null;
        next: string | null;
    };
    meta: {
        currentPage: number;
        from: number;
        lastPage: number;
        perPage: number;
        to: number;
        total: number;
        links: Array<{
            url: string | null;
            label: string;
            page: number | null;
            active: boolean;
        }>;
        path: string;
    };
}
