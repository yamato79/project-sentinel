// declare type ModelTableMeta = {
//     searchQuery: string;
//     currentPage: number;
//     pageSize: number;
//     totalRows: number;
//     fromResult: number;
//     toResult: number;
//     isLoading: boolean;
// };

declare type ModelTableColumn = {
    key: string;
    title: string;
    align?: "left" | "center" | "right";
    fullWidth?: boolean;
};

declare type ModelTableRow = {
    [key: string]: any;
};

declare type ModelTableMeta = {
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    next_page_url: string;
    path: string;
    per_page: number; 
    prev_page_url: string;
    to: number;
    total: number;
}

declare type ModelTableLinks = {
    first: string;
    last: string;
    prev: string;
    next: string;
}
