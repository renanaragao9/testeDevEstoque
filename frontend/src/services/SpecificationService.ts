import api from '@/config/api';
import type { Specification, SpecificationPayload, SpecificationResponse } from '@/types/specification';
import type { BaseFilters } from '@/types/global/filters';

export class SpecificationService {
    private static readonly BASE_URL = '/specifications';

    private static preparePayload(specificationData: SpecificationPayload | Partial<SpecificationPayload>): SpecificationPayload | Partial<SpecificationPayload> {
        return specificationData;
    }

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    static async getSpecifications(filters: Partial<BaseFilters> = {}): Promise<SpecificationResponse> {
        const params = new URLSearchParams();

        if (filters.search) params.append('search', filters.search);
        if (filters.orderByColumn) params.append('order_by_column', filters.orderByColumn);
        if (filters.orderByDirection) params.append('order_by_direction', filters.orderByDirection);
        if (filters.perPage) params.append('per_page', filters.perPage.toString());
        if (filters.page) params.append('page', filters.page.toString());
        if (filters.paginate !== undefined) params.append('paginate', filters.paginate ? '1' : '0');

        const queryString = params.toString();
        const url = queryString ? `${this.BASE_URL}?${queryString}` : this.BASE_URL;

        const response = await api.get<SpecificationResponse>(url);
        return response.data;
    }

    static async getSpecification(id: number): Promise<{ data: Specification; message: string }> {
        const response = await api.get<{ data: Specification; message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }

    static async createSpecification(specificationData: SpecificationPayload): Promise<{ data: Specification; message: string }> {
        const payload = this.preparePayload(specificationData);
        const response = await api.post<{ data: Specification; message: string }>(this.BASE_URL, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async updateSpecification(id: number, specificationData: Partial<SpecificationPayload>): Promise<{ data: Specification; message: string }> {
        const payload = this.preparePayload(specificationData);
        const response = await api.put<{ data: Specification; message: string }>(`${this.BASE_URL}/${id}`, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async deleteSpecification(id: number): Promise<{ message: string }> {
        const response = await api.delete<{ message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }
}
