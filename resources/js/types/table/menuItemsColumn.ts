import { h } from 'vue';
import { ColumnDef } from "@tanstack/vue-table";
import DataTableDropDown from '@/components/datatable/DataTableDropDown.vue';
import { Switch } from '@/components/ui/switch';
import { MenuItem } from '../menuItem';

export const menuItemsColumn = ({ handleEdit, handleEditAvailable, handleDelete }: { handleEdit: (menuItem: MenuItem) => void; handleEditAvailable: (menuItem: MenuItem) => void; handleDelete: (menuItem: MenuItem) => void; }): ColumnDef<MenuItem>[] => [
    {
        accessorKey: "category_name",
        header: "Category"
    },
    {
        accessorKey: "name",
        header: "Name"
    },
    {
        accessorKey: "slug",
        header: "Slug"
    },
    {
        accessorKey: "price",
        header: "Price"
    },
    {
        accessorKey: "image_url",
        header: "Image",
        cell: ({ row }) => {
            const menuItem = row.original;
            return h('img', {
                src: "/storage/" + menuItem.image_url,
                alt: menuItem.name,
                class: 'w-16 h-16 object-cover'
            });
        }
    },
    {
        accessorKey: "is_available",
        header: "Available",
        cell: ({ row }) => {
            const menuItem = row.original;
            return h(Switch, {
                modelValue: menuItem.is_available,
                'onUpdate:modelValue': (value: boolean) => {
                    menuItem.is_available = value;
                    handleEditAvailable({ ...menuItem, is_available: value});
                }
            });
        }
    },
    {
        accessorKey: 'actions',
        header: '',
        cell: ({ row }) => {
            const menuItem = row.original;
            return h(DataTableDropDown, {
                onEdit: () => handleEdit(menuItem),
                onDelete: () => handleDelete(menuItem),
            });
        }   
    }
]