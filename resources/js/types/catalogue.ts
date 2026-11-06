export interface CatalogueRecord {
    id: number;
    name?: string;
    title?: string;
    description: string | null;
    shows_count?: number;
}

export interface PaginatedRecords {
    data: CatalogueRecord[];
    current_page: number;
    last_page: number;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

export interface ShowRecord extends CatalogueRecord {
    date_start: string | null;
    date_end: string | null;
    company: CatalogueRecord | null;
    season: CatalogueRecord | null;
    period: CatalogueRecord | null;
    playwright: CatalogueRecord | null;
    venue: CatalogueRecord | null;
}
