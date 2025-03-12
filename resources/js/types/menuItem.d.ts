import { Meta } from ".";

type MenuItem = {
    id: number;
    category_id: number;
    name: string;
    slug: string;
    price: number;
    image_url: string;
    description: string;
    is_available: boolean;
    is_published: boolean;
    created_at: string;
    updated_at: string;
}

interface MenuItemResponse extends Meta {
    data: MenuItem<TData>;
}