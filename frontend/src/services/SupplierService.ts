import api from '@/config/api';
import type { Supplier, SupplierPayload, SupplierResponse } from '@/types/supplier';
import type { BaseFilters } from '@/types/global/filters';

export class SupplierService {
    private static readonly BASE_URL = '/suppliers';

    private static preparePayload(supplierData: SupplierPayload | Partial<SupplierPayload>): SupplierPayload | Partial<SupplierPayload> {
        return supplierData;
    }

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    static async getSuppliers(filters: Partial<BaseFilters> = {}): Promise<SupplierResponse> {
        const params = new URLSearchParams();

        if (filters.search) params.append('search', filters.search);
        if (filters.orderByColumn) params.append('order_by_column', filters.orderByColumn);
        if (filters.orderByDirection) params.append('order_by_direction', filters.orderByDirection);
        if (filters.perPage) params.append('per_page', filters.perPage.toString());
        if (filters.page) params.append('page', filters.page.toString());
        if (filters.paginate !== undefined) params.append('paginate', filters.paginate ? '1' : '0');

        const queryString = params.toString();
        const url = queryString ? `${this.BASE_URL}?${queryString}` : this.BASE_URL;

        const response = await api.get<SupplierResponse>(url);
        return response.data;
    }

    static async getSupplier(id: number): Promise<{ data: Supplier; message: string }> {
        const response = await api.get<{ data: Supplier; message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }

    static async createSupplier(supplierData: SupplierPayload): Promise<{ data: Supplier; message: string }> {
        const payload = this.preparePayload(supplierData);
        const response = await api.post<{ data: Supplier; message: string }>(this.BASE_URL, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async updateSupplier(id: number, supplierData: Partial<SupplierPayload>): Promise<{ data: Supplier; message: string }> {
        const payload = this.preparePayload(supplierData);
        const response = await api.put<{ data: Supplier; message: string }>(`${this.BASE_URL}/${id}`, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async deleteSupplier(id: number): Promise<{ message: string }> {
        const response = await api.delete<{ message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }
}
