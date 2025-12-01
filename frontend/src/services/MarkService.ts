import api from '@/config/api';
import type { Mark, MarkPayload, MarkResponse } from '@/types/mark';
import type { BaseFilters } from '@/types/global/filters';

export class MarkService {
    private static readonly BASE_URL = '/marks';

    private static preparePayload(markData: MarkPayload | Partial<MarkPayload>): MarkPayload | Partial<MarkPayload> {
        return markData;
    }

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    static async getMarks(filters: Partial<BaseFilters> = {}): Promise<MarkResponse> {
        const params = new URLSearchParams();

        if (filters.search) params.append('search', filters.search);
        if (filters.orderByColumn) params.append('order_by_column', filters.orderByColumn);
        if (filters.orderByDirection) params.append('order_by_direction', filters.orderByDirection);
        if (filters.perPage) params.append('per_page', filters.perPage.toString());
        if (filters.page) params.append('page', filters.page.toString());
        if (filters.paginate !== undefined) params.append('paginate', filters.paginate ? '1' : '0');

        const queryString = params.toString();
        const url = queryString ? `${this.BASE_URL}?${queryString}` : this.BASE_URL;

        const response = await api.get<MarkResponse>(url);
        return response.data;
    }

    static async getMark(id: number): Promise<{ data: Mark; message: string }> {
        const response = await api.get<{ data: Mark; message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }

    static async createMark(markData: MarkPayload): Promise<{ data: Mark; message: string }> {
        const payload = this.preparePayload(markData);
        const response = await api.post<{ data: Mark; message: string }>(this.BASE_URL, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async updateMark(id: number, markData: Partial<MarkPayload>): Promise<{ data: Mark; message: string }> {
        const payload = this.preparePayload(markData);
        const response = await api.put<{ data: Mark; message: string }>(`${this.BASE_URL}/${id}`, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async deleteMark(id: number): Promise<{ message: string }> {
        const response = await api.delete<{ message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }
}
