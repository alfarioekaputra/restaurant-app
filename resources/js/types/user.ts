import { ColumnDef } from '@tanstack/vue-table';
import { User } from '.';
import DataTableDropDown from '@/components/datatable/DataTableDropDown.vue';
import { h } from 'vue';

export const userColumns: ColumnDef<User>[] = [
  {
    accessorKey: 'email',
    header: () => 'Email',
  },
  {
    accessorKey: 'name',
    header: () => 'Name',
  },
  {
    id: 'role',
    header: () => 'Role',
    cell: ({ row }) => {
      const user = row.original;
      // Get roles from the user model
      if (user.roles && user.roles.length > 0) {
        return user.roles[0]; // Now roles contains the role names
      }
      return 'No role';
    }
  },
  {
    accessorKey: 'actions',
    header: () => '',
    cell: ({ row }) => {
      const user = row.original;
      return h(DataTableDropDown, {
        editUrl: `/admin/users/${user.id}/edit`,
        deleteUrl: `/admin/users/${user.id}`,
        userId: user.id
      });
    }
  }
];