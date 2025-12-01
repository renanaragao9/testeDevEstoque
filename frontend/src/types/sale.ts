export interface Sale {
    id: number;
    invoiceNumber: string;
    saleDate: string;
    totalAmount: number;
    customerId?: number;
    customer?: {
        id: number;
        name: string;
        email: string;
        phone: string;
    };
    stockMovements?: SaleItem[];
    createdAt?: string;
}

export interface SaleItem {
    id?: number;
    productId: number;
    quantity: number;
    product?: {
        id: number;
        name: string;
    };
}

export interface SalePayload {
    invoiceNumber: string;
    saleDate: string;
    totalAmount: number;
    customerId?: number;
    items: {
        productId: number;
        quantity: number;
    }[];
}

export interface SaleResponse {
    data: Sale[];
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
