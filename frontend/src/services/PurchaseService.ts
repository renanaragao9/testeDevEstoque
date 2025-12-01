import api from '@/config/api';
import type { Purchase, PurchasePayload, PurchaseResponse } from '@/types/purchase';
import type { BaseFilters } from '@/types/global/filters';

export class PurchaseService {
    private static readonly BASE_URL = '/purchases';

    private static preparePayload(purchaseData: PurchasePayload | Partial<PurchasePayload>) {
        if (!purchaseData) return {};

        return {
            invoice_number: purchaseData.invoiceNumber,
            purchase_date: purchaseData.purchaseDate,
            total_amount: purchaseData.totalAmount,
            supplier_id: purchaseData.supplierId,
            items:
                purchaseData.items?.map((item) => ({
                    product_id: item.productId,
                    warehouse_id: item.warehouseId,
                    quantity: item.quantity
                })) || []
        };
    }

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    static async getPurchases(filters: Partial<BaseFilters> = {}): Promise<PurchaseResponse> {
        const params = new URLSearchParams();

        if (filters.search) params.append('search', filters.search);
        if (filters.orderByColumn) params.append('order_by_column', filters.orderByColumn);
        if (filters.orderByDirection) params.append('order_by_direction', filters.orderByDirection);
        if (filters.perPage) params.append('per_page', filters.perPage.toString());
        if (filters.page) params.append('page', filters.page.toString());
        if (filters.paginate !== undefined) params.append('paginate', filters.paginate ? '1' : '0');

        const queryString = params.toString();
        const url = queryString ? `${this.BASE_URL}?${queryString}` : this.BASE_URL;

        const response = await api.get<PurchaseResponse>(url);
        return response.data;
    }

    static async getPurchase(id: number): Promise<{ data: Purchase; message: string }> {
        const response = await api.get<{ data: Purchase; message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }

    static async createPurchase(purchaseData: PurchasePayload): Promise<{ data: Purchase; message: string }> {
        const payload = this.preparePayload(purchaseData);
        const response = await api.post<{ data: Purchase; message: string }>(this.BASE_URL, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async deletePurchase(id: number): Promise<{ message: string }> {
        const response = await api.delete<{ message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }
}
