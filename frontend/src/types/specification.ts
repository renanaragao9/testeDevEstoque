export interface Specification {
    id: number;
    name: string;
    is_active: boolean;
    createdAt?: string;
}

export interface SpecificationPayload {
    name: string;
    is_active: boolean;
}

export interface SpecificationResponse {
    data: Specification[];
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
