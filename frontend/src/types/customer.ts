export interface Customer {
    id: number;
    name: string;
    email: string;
    phone: string;
    createdAt?: string;
}

export interface CustomerPayload {
    name: string;
    email: string;
    phone: string;
}

export interface CustomerResponse {
    data: Customer[];
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
