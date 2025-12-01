import api from '@/config/api';
import type { Warehouse, WarehousePayload, WarehouseResponse } from '@/types/warehouse';
import type { BaseFilters } from '@/types/global/filters';

export class WarehouseService {
    private static readonly BASE_URL = '/warehouses';

    private static preparePayload(warehouseData: WarehousePayload | Partial<WarehousePayload>): WarehousePayload | Partial<WarehousePayload> {
        return warehouseData;
    }

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    static async getWarehouses(filters: Partial<BaseFilters> = {}): Promise<WarehouseResponse> {
        const params = new URLSearchParams();

        if (filters.search) params.append('search', filters.search);
        if (filters.orderByColumn) params.append('order_by_column', filters.orderByColumn);
        if (filters.orderByDirection) params.append('order_by_direction', filters.orderByDirection);
        if (filters.perPage) params.append('per_page', filters.perPage.toString());
        if (filters.page) params.append('page', filters.page.toString());
        if (filters.paginate !== undefined) params.append('paginate', filters.paginate ? '1' : '0');

        const queryString = params.toString();
        const url = queryString ? `${this.BASE_URL}?${queryString}` : this.BASE_URL;

        const response = await api.get<WarehouseResponse>(url);
        return response.data;
    }

    static async getWarehouse(id: number): Promise<{ data: Warehouse; message: string }> {
        const response = await api.get<{ data: Warehouse; message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }

    static async createWarehouse(warehouseData: WarehousePayload): Promise<{ data: Warehouse; message: string }> {
        const payload = this.preparePayload(warehouseData);
        const response = await api.post<{ data: Warehouse; message: string }>(this.BASE_URL, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async updateWarehouse(id: number, warehouseData: Partial<WarehousePayload>): Promise<{ data: Warehouse; message: string }> {
        const payload = this.preparePayload(warehouseData);
        const response = await api.put<{ data: Warehouse; message: string }>(`${this.BASE_URL}/${id}`, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async deleteWarehouse(id: number): Promise<{ message: string }> {
        const response = await api.delete<{ message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }
}
