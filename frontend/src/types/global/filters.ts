export interface BaseFilters {
    search?: string;
    orderByColumn?: string;
    orderByDirection?: 'asc' | 'desc';
    perPage?: number;
    page?: number;
    paginate?: boolean;
}
