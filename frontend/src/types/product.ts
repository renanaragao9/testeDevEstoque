export interface Product {
    id: number;
    name: string;
    description?: string;
    price_sale: number;
    product_type_id: number;
    mark_id: number;
    createdAt?: string;
    productType?: {
        id: number;
        name: string;
    };
    mark?: {
        id: number;
        name: string;
    };
    specifications?: ProductSpecification[];
}

export interface ProductSpecification {
    id: number;
    name: string;
    pivot: {
        value: string;
    };
}

export interface ProductPayload {
    name: string;
    description?: string;
    price_sale: number;
    product_type_id: number;
    mark_id: number;
    specifications?: SpecificationSync[];
}

export interface SpecificationSync {
    specification_id: number;
    value: string;
}

export interface ProductResponse {
    data: Product[];
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
