export interface Mark {
    id: number;
    name: string;
    description?: string;
    country?: string;
    createdAt?: string;
}

export interface MarkPayload {
    name: string;
    description?: string;
    country?: string;
}

export interface MarkResponse {
    data: Mark[];
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
