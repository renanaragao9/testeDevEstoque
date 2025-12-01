import api from '@/config/api';
import type { DashboardResponse, DashboardData, SalesReport, GeneralStats, TopSellingProduct } from '@/types/dashboard';

export class DashboardService {
    private static readonly BASE_URL = '/dashboard';

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    /**
     * Busca todos os dados do dashboard
     */
    static async getDashboardData(): Promise<DashboardData> {
        const response = await api.get<DashboardResponse>(this.BASE_URL, {
            headers: this.getHeaders()
        });
        return response.data.data as DashboardData;
    }

    /**
     * Busca relatório de vendas (quantidade total vendida e valor total)
     */
    static async getSalesReport(): Promise<SalesReport> {
        const response = await api.get<DashboardResponse>(`${this.BASE_URL}/sales-report`, {
            headers: this.getHeaders()
        });
        return response.data.data as SalesReport;
    }

    /**
     * Busca estatísticas gerais do sistema
     */
    static async getGeneralStats(): Promise<GeneralStats> {
        const response = await api.get<DashboardResponse>(`${this.BASE_URL}/general-stats`, {
            headers: this.getHeaders()
        });
        return response.data.data as GeneralStats;
    }

    /**
     * Busca produtos mais vendidos ordenados do mais para o menos vendido
     */
    static async getTopSellingProducts(): Promise<TopSellingProduct[]> {
        const response = await api.get<DashboardResponse>(`${this.BASE_URL}/top-selling-products`, {
            headers: this.getHeaders()
        });
        return response.data.data as TopSellingProduct[];
    }
}
