export interface SalesReport {
    total_quantity_sold: number;
    total_sales_amount: number;
}

export interface GeneralStats {
    total_purchases: number;
    total_sales: number;
    total_customers: number;
    total_products: number;
}

export interface TopSellingProduct {
    product_id: number;
    product_name: string;
    total_sold: number;
}

export interface DashboardData {
    sales_report: SalesReport;
    general_stats: GeneralStats;
    top_selling_products: TopSellingProduct[];
}

export interface DashboardResponse {
    status: string;
    message: string;
    data: DashboardData | SalesReport | GeneralStats | TopSellingProduct[];
}
