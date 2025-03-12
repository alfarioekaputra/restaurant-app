<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import Datatable from '@/components/datatable/Datatable.vue';
import { categoryColumns } from '@/types/table/categoryColumn';
import CategoryFormModal from '@/components/category/FormModal.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { showConfirm, showSuccess } from '@/utils/notifications';
import { Category, CategoryResponse } from '@/types/category';

defineProps<{
    categories: CategoryResponse;
    filters: {
        search: string;
        per_page: number;
        filter?: string;
    }
}>();

const emit = defineEmits(["edit", "delete"]);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: route('categories.index'),
    },
];

// Modal state
const showCategoryModal = ref(false);
const selectedCategoryId = ref<number | undefined>(undefined);
const isEditCategory = ref(false);

// Open modal for creating a new category
const openCreateCategoryModal = () => {
    selectedCategoryId.value = undefined;
    isEditCategory.value = false;
    showCategoryModal.value = true;
};

// Handle modal close
const handleModalClose = () => {
    showCategoryModal.value = false;
};

// Handle category saved (created or updated)
const handleCategorySaved = () => {
    // The notification is now handled in the CategoryFormModal component
};

const handleEdit = (category: Category) => {
  emit("edit", category);
  showCategoryModal.value = true;
  selectedCategoryId.value = category.id;
  isEditCategory.value = true;
};

const handleDelete = (category: Category) => {
  emit("delete", category);
  showConfirm(
    "You won't be able to revert this!",
    'Are you sure?',
    'Yes, delete it!',
    'Cancel'
  ).then((result) => {
    if (result.isConfirmed) {
      router.delete(route('categories.destroy', {id: category.id}), {
        onSuccess: () => {
          showSuccess('The category has been deleted.', 'Deleted!');
        }
      })
    }
  })
};
</script>

<template>
    <Head title="Category List" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle>Category List</CardTitle>
                    <Button @click="openCreateCategoryModal" class="flex items-center gap-1">
                        <Plus class="h-4 w-4" />
                        Add Category
                    </Button>
                </CardHeader>
                <CardContent>
                    <Datatable 
                        :columns="categoryColumns({ handleEdit, handleDelete})" 
                        :meta="categories" 
                        :filters="filters"
                        route-name="categories.index"
                        :show-filter="true"
                    />
                </CardContent>
            </Card>

            <!-- Category Form Modal -->
            <CategoryFormModal
                :show="showCategoryModal"
                :categoryId="selectedCategoryId"
                :isEdit="isEditCategory"
                @close="handleModalClose"
                @saved="handleCategorySaved"
            />
        </div>
    </AppLayout>
</template>