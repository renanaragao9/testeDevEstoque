export interface Product {
    name: string;
    total: number;
    averageCost: number;
}

export interface Warehouse {
    id: number;
    name: string;
    location: string;
    totalStock: number;
    totalStockValue: number;
    products?: Product[];
    createdAt?: string;
}

export interface WarehousePayload {
    name: string;
    location: string;
}

export interface WarehouseResponse {
    data: Warehouse[];
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
