import { Meta } from ".";

type Category = {
    id: number;
    name: string;
    slug: string;
}

interface CategoryResponse extends Meta {
    data: Category<TData>
}