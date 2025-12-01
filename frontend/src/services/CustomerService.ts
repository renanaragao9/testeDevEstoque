import api from '@/config/api';
import type { Customer, CustomerPayload, CustomerResponse } from '@/types/customer';
import type { BaseFilters } from '@/types/global/filters';

export class CustomerService {
    private static readonly BASE_URL = '/customers';

    private static preparePayload(customerData: CustomerPayload | Partial<CustomerPayload>): CustomerPayload | Partial<CustomerPayload> {
        return customerData;
    }

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    static async getCustomers(filters: Partial<BaseFilters> = {}): Promise<CustomerResponse> {
        const params = new URLSearchParams();

        if (filters.search) params.append('search', filters.search);
        if (filters.orderByColumn) params.append('order_by_column', filters.orderByColumn);
        if (filters.orderByDirection) params.append('order_by_direction', filters.orderByDirection);
        if (filters.perPage) params.append('per_page', filters.perPage.toString());
        if (filters.page) params.append('page', filters.page.toString());
        if (filters.paginate !== undefined) params.append('paginate', filters.paginate ? '1' : '0');

        const queryString = params.toString();
        const url = queryString ? `${this.BASE_URL}?${queryString}` : this.BASE_URL;

        const response = await api.get<CustomerResponse>(url);
        return response.data;
    }

    static async getCustomer(id: number): Promise<{ data: Customer; message: string }> {
        const response = await api.get<{ data: Customer; message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }

    static async createCustomer(customerData: CustomerPayload): Promise<{ data: Customer; message: string }> {
        const payload = this.preparePayload(customerData);
        const response = await api.post<{ data: Customer; message: string }>(this.BASE_URL, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async updateCustomer(id: number, customerData: Partial<CustomerPayload>): Promise<{ data: Customer; message: string }> {
        const payload = this.preparePayload(customerData);
        const response = await api.put<{ data: Customer; message: string }>(`${this.BASE_URL}/${id}`, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async deleteCustomer(id: number): Promise<{ message: string }> {
        const response = await api.delete<{ message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }
}
