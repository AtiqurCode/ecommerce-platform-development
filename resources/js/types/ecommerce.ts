export type CategoryOption = {
    id: number;
    name: string;
    subcategories?: SubcategoryOption[];
};

export type SubcategoryOption = {
    id: number;
    name: string;
    category_id?: number;
};

export type Paginated<T> = {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
};
