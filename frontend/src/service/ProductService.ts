export interface Order {
    id: string;
    productCode: string;
    date: string;
    amount: number;
    quantity: number;
    customer: string;
    status: string;
}

export interface Product {
    id: string;
    code: string;
    name: string;
    description: string;
    image: string;
    price: number;
    category: string;
    quantity: number;
    inventoryStatus: string;
    rating: number;
    orders?: Order[];
}

export const ProductService = {
    getProductsData(): Product[] {
        return [
            {
                id: '1000',
                code: 'f230fh0g3',
                name: 'Bamboo Watch',
                description: 'Product Description',
                image: 'bamboo-watch.jpg',
                price: 65,
                category: 'Accessories',
                quantity: 24,
                inventoryStatus: 'INSTOCK',
                rating: 5
            },
            {
                id: '1001',
                code: 'nvklal433',
                name: 'Black Watch',
                description: 'Product Description',
                image: 'black-watch.jpg',
                price: 72,
                category: 'Accessories',
                quantity: 61,
                inventoryStatus: 'INSTOCK',
                rating: 4
            }
        ];
    },

    getProductsWithOrdersData(): Product[] {
        return [
            {
                id: '1000',
                code: 'f230fh0g3',
                name: 'Bamboo Watch',
                description: 'Product Description',
                image: 'bamboo-watch.jpg',
                price: 65,
                category: 'Accessories',
                quantity: 24,
                inventoryStatus: 'INSTOCK',
                rating: 5,
                orders: [
                    {
                        id: '1000-0',
                        productCode: 'f230fh0g3',
                        date: '2020-09-13',
                        amount: 65,
                        quantity: 1,
                        customer: 'David James',
                        status: 'PENDING'
                    },
                    {
                        id: '1000-1',
                        productCode: 'f230fh0g3',
                        date: '2020-05-14',
                        amount: 130,
                        quantity: 2,
                        customer: 'Leon Rodrigues',
                        status: 'DELIVERED'
                    }
                ]
            }
        ];
    },

    getProductsMini(): Promise<Product[]> {
        return Promise.resolve(this.getProductsData().slice(0, 5));
    },

    getProductsSmall(): Promise<Product[]> {
        return Promise.resolve(this.getProductsData().slice(0, 10));
    },

    getProducts(): Promise<Product[]> {
        return Promise.resolve(this.getProductsData());
    },

    getProductsWithOrdersSmall(): Promise<Product[]> {
        return Promise.resolve(this.getProductsWithOrdersData().slice(0, 10));
    },

    getProductsWithOrders(): Promise<Product[]> {
        return Promise.resolve(this.getProductsWithOrdersData());
    }
};
