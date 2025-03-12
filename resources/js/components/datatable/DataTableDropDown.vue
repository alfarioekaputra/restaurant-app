<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { MoreHorizontal } from 'lucide-vue-next'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import UserFormModal from '@/components/user/UserFormModal.vue'
import { showConfirm, showSuccess } from '@/utils/notifications'

defineProps<{
  onEdit: () => void;
  onDelete: () => void;
}>()

const emit = defineEmits(['edit', 'delete']);

// const handleEdit = () => {
//   emit('edit')
// }

// const handleDelete = () => {
//   showConfirm(
//     "You won't be able to revert this!",
//     'Are you sure?',
//     'Yes, delete it!',
//     'Cancel'
//   ).then((result) => {
//     if (result.isConfirmed) {
//       router.delete(route('users.destroy', {id: props.primaryKey}), {
//         onSuccess: () => {
//           showSuccess('The user has been deleted.', 'Deleted!');
//         }
//       })
//     }
//   })
// }

// const handleModalClose = () => {
//   showEditModal.value = false
// }

// const handleUserSaved = () => {
//   // Modal will be closed by the form component
// }
</script>

<template>
  <!-- User Edit Modal -->
  <!-- <UserFormModal
    :show="showEditModal"
    :primaryKey="primaryKey"
    :isEdit="true"
    @close="handleModalClose"
    @saved="handleUserSaved"
  /> -->

  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" class="w-8 h-8 p-0">
        <span class="sr-only">Open menu</span>
        <MoreHorizontal class="w-4 h-4" />
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end">
      <DropdownMenuLabel>Actions</DropdownMenuLabel>
      <DropdownMenuItem @click="onEdit">
        Edit
      </DropdownMenuItem>
      <DropdownMenuSeparator />
      <DropdownMenuItem class="text-red-600" @click="onDelete">
        Delete
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>