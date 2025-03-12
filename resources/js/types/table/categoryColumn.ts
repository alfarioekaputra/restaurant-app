import { h } from 'vue';
import { ColumnDef } from "@tanstack/vue-table";
import DataTableDropDown from '@/components/datatable/DataTableDropDown.vue';
import { Category } from '../category';

export const categoryColumns = ({ handleEdit, handleDelete }: { handleEdit: (category: Category) => void; handleDelete: (category: Category) => void; }): ColumnDef<Category>[] => [
    {
        accessorKey: "name",
        header: "Name"
    },
    {
        accessorKey: "slug",
        header: "Slug"
    },
    {
        accessorKey: 'actions',
        header: '',
        cell: ({ row }) => {
            const category = row.original;
            return h(DataTableDropDown, {
                onEdit: () => handleEdit(category),
                onDelete: () => handleDelete(category),
            });
        }   
    }
]