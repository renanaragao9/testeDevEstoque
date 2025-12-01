import api from '@/config/api';
import type { DashboardResponse, DashboardData, SalesReport, GeneralStats, TopSellingProduct } from '@/types/dashboard';

export class DashboardService {
    private static readonly BASE_URL = '/dashboard';

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    static async getDashboardData(): Promise<DashboardData> {
        const response = await api.get<DashboardResponse>(this.BASE_URL, {
            headers: this.getHeaders()
        });
        return response.data.data as DashboardData;
    }

    static async getSalesReport(): Promise<SalesReport> {
        const response = await api.get<DashboardResponse>(`${this.BASE_URL}/sales-report`, {
            headers: this.getHeaders()
        });
        return response.data.data as SalesReport;
    }

    static async getGeneralStats(): Promise<GeneralStats> {
        const response = await api.get<DashboardResponse>(`${this.BASE_URL}/general-stats`, {
            headers: this.getHeaders()
        });
        return response.data.data as GeneralStats;
    }

    static async getTopSellingProducts(): Promise<TopSellingProduct[]> {
        const response = await api.get<DashboardResponse>(`${this.BASE_URL}/top-selling-products`, {
            headers: this.getHeaders()
        });
        return response.data.data as TopSellingProduct[];
    }

    static async downloadStockExitReport(): Promise<void> {
        try {
            const response = await api.get(`${this.BASE_URL}/export-stock-exit`, {
                responseType: 'blob',
                headers: {
                    Accept: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                }
            });

            const blob = new Blob([response.data as BlobPart], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });

            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;

            const timestamp = new Date().toISOString().slice(0, 19).replace(/:/g, '-');
            link.download = `relatorio_saidas_estoque_${timestamp}.xlsx`;

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            window.URL.revokeObjectURL(url);
        } catch {
            throw new Error('Erro ao fazer download do relatório de estoque');
        }
    }
}
