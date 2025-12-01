import api from '@/config/api';
import type { Sale, SalePayload, SaleResponse } from '@/types/sale';
import type { BaseFilters } from '@/types/global/filters';

export class SaleService {
    private static readonly BASE_URL = '/sales';

    private static preparePayload(saleData: SalePayload | Partial<SalePayload>): Record<string, unknown> {
        if (!saleData) return {};

        return {
            invoice_number: saleData.invoiceNumber,
            sale_date: saleData.saleDate,
            total_amount: saleData.totalAmount,
            status: saleData.status || 'pending',
            customer_id: saleData.customerId || null,
            items:
                saleData.items?.map((item) => ({
                    product_id: item.productId,
                    quantity: item.quantity
                })) || []
        };
    }

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    static async getSales(filters: Partial<BaseFilters> = {}): Promise<SaleResponse> {
        const params = new URLSearchParams();

        if (filters.search) params.append('search', filters.search);
        if (filters.orderByColumn) params.append('order_by_column', filters.orderByColumn);
        if (filters.orderByDirection) params.append('order_by_direction', filters.orderByDirection);
        if (filters.perPage) params.append('per_page', filters.perPage.toString());
        if (filters.page) params.append('page', filters.page.toString());
        if (filters.paginate !== undefined) params.append('paginate', filters.paginate ? '1' : '0');

        const queryString = params.toString();
        const url = queryString ? `${this.BASE_URL}?${queryString}` : this.BASE_URL;

        const response = await api.get<SaleResponse>(url);
        return response.data;
    }

    static async getSale(id: number): Promise<{ data: Sale; message: string }> {
        const response = await api.get<{ data: Sale; message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }

    static async createSale(saleData: SalePayload): Promise<{ data: Sale; message: string }> {
        const payload = this.preparePayload(saleData);
        const response = await api.post<{ data: Sale; message: string }>(this.BASE_URL, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async deleteSale(id: number): Promise<{ message: string }> {
        const response = await api.delete<{ message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }
}
