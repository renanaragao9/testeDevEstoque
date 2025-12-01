export interface Purchase {
    id: number;
    invoiceNumber: string;
    purchaseDate: string;
    totalAmount: number;
    supplierId: number;
    supplier?: {
        id: number;
        name: string;
        email: string;
        phone: string;
        address: string;
    };
    stockMovements?: PurchaseItem[];
    createdAt?: string;
}

export interface PurchaseItem {
    id?: number;
    productId: number;
    warehouseId: number;
    quantity: number;
    product?: {
        id: number;
        name: string;
    };
    warehouse?: {
        id: number;
        name: string;
    };
}

export interface PurchasePayload {
    invoiceNumber: string;
    purchaseDate: string;
    totalAmount: number;
    supplierId: number;
    items: {
        productId: number;
        warehouseId: number;
        quantity: number;
    }[];
}

export interface PurchaseResponse {
    data: Purchase[];
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
