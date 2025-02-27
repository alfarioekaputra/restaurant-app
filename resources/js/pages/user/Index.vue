<script setup lang="ts">
import Datatable from '@/components/datatable/Datatable.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { User, type BreadcrumbItem } from '@/types';
import { userColumns } from '@/types/user';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';
import UserFormModal from '@/components/user/UserFormModal.vue';


defineProps<{
    users: User[];
    filters: {
        search: string;
        per_page: number;
        filter?: string;
    }
    roles: Array<{ id: number | string; name: string }>
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: route('users.index'),
    },
];

// Modal state
const showUserModal = ref(false);
const selectedUserId = ref<number | undefined>(undefined);
const isEditMode = ref(false);

// Open modal for creating a new user
const openCreateUserModal = () => {
    selectedUserId.value = undefined;
    isEditMode.value = false;
    showUserModal.value = true;
};

// Open modal for editing a user
const openEditUserModal = (userId: number) => {
    selectedUserId.value = userId;
    isEditMode.value = true;
    showUserModal.value = true;
};

// Handle modal close
const handleModalClose = () => {
    showUserModal.value = false;
};

// Handle user saved (created or updated)
const handleUserSaved = () => {
    // The notification is now handled in the UserEditForm component
};
</script>

<template>
    <Head title="User List" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle>Users List</CardTitle>
                    <Button @click="openCreateUserModal" class="flex items-center gap-1">
                        <Plus class="h-4 w-4" />
                        Add User
                    </Button>
                </CardHeader>
                <CardContent>
                    <Datatable 
                        :columns="userColumns" 
                        :meta="users" 
                        :filters="filters"
                        route-name="users.index"
                        :show-filter="true"
                        :filter-options="roles"
                        filter-label="Role"
                        filter-placeholder="Filter by role"
                    />
                </CardContent>
            </Card>
            
            <!-- User Form Modal -->
            <UserFormModal
                :show="showUserModal"
                :userId="selectedUserId"
                :isEdit="isEditMode"
                @close="handleModalClose"
                @saved="handleUserSaved"
            />
        </div>
    </AppLayout>
</template>