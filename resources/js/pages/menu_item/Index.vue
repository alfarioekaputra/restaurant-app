<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import Datatable from '@/components/datatable/Datatable.vue';
import { menuItemsColumn } from '@/types/table/menuItemsColumn';
// import MenuItemFormModal from '@/components/menu_item/FormModal.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { showConfirm, showError, showSuccess } from '@/utils/notifications';
import FormModal from '@/components/FormModal.vue';
import MenuItemForm from '@/components/menu-item/_form.vue';
import { MenuItem, MenuItemResponse } from '@/types/menuItem';

defineProps<{
    menuItems: MenuItemResponse;
    filters: {
        search: string;
        per_page: number;
        filter?: string;
    }
}>();

const page = usePage()

const emit = defineEmits(["edit", "delete", "editAvailable"]);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu Items',
        href: route('menu-items.index'),
    },
];

// Modal state
const showModal = ref(false);
const selectedId = ref<number | undefined>(undefined);
const isEdit = ref(false);

// Open modal for creating a new category
const openCreateCategoryModal = () => {
    selectedId.value = undefined;
    isEdit.value = false;
    showModal.value = true;
};

// Handle modal close
const handleModalClose = () => {
    showModal.value = false;
};

// Handle user saved (created or updated)
const handleSaved = () => {
    // The notification is now handled in the UserEditForm component
};

const handleEdit = (menuItem: MenuItem) => {
  emit("edit", menuItem);
  showModal.value = true;
  selectedId.value = menuItem.id;
  isEdit.value = true;
};

const handleEditAvailable = async (menuItem: MenuItem) => {
  emit("editAvailable", menuItem);
  try {
    router.put(route('menu-items.update-available', {id: menuItem.id}), {
        is_available: menuItem.is_available
    }, {
        onSuccess: () => {
            showSuccess('Menu item availability has been updated');
        },
        onError: (errors) => {
            console.error('Form errors:', errors);
            showError('Failed to update menu item availability. Please check the form for errors.');
        }
    });
  } catch (error) {
    showError('Failed to update menu item availability');
  }
};

const handleDelete = (menuItem: MenuItem) => {
  emit("delete", menuItem);
  showConfirm(
    "You won't be able to revert this!",
    'Are you sure?',
    'Yes, delete it!',
    'Cancel'
  ).then((result) => {
    if (result.isConfirmed) {
      router.delete(route('menu-items.destroy', {id: menuItem.id}), {
        onSuccess: () => {
          showSuccess('The menu item has been deleted.', 'Deleted!');
        }
      })
    }
  })
};
</script>

<template>
    <Head title="Menu Item List" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle>Menu List</CardTitle>
                    <Button @click="openCreateCategoryModal" class="flex items-center gap-1">
                        <Plus class="h-4 w-4" />
                        Add Menu
                    </Button>
                </CardHeader>
                <CardContent>
                    <Datatable 
                        :columns="menuItemsColumn({ handleEdit, handleEditAvailable, handleDelete })" 
                        :meta="menuItems" 
                        :filters="filters"
                        route-name="menu-items.index"
                        :show-filter="true"
                    />
                </CardContent>
            </Card>

            <!-- Form Modal -->
            <FormModal
                :show="showModal"
                :isEdit="isEdit"
                title="Menu Item"
                @close="handleModalClose"
            >
                <MenuItemForm 
                    :show="showModal" 
                    :primaryKey="selectedId" 
                    :isEdit="isEdit"
                    @close="handleModalClose"
                    @saved="handleSaved"
                />
            </FormModal>
        </div>
    </AppLayout>
</template>