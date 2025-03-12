<script setup lang="ts">
import { ref, watch } from 'vue';
import { 
  Dialog, 
  DialogContent, 
  DialogHeader, 
  DialogTitle,
  DialogDescription
} from '@/components/ui/dialog';
import UserEditForm from './UserEditForm.vue';

const props = defineProps<{
  show: boolean;
  primaryKey: number;
  isEdit?: boolean;
}>();

const emit = defineEmits(['close', 'saved']);

const handleClose = () => {
  emit('close');
};

const handleSaved = () => {
  emit('saved');
};
</script>

<template>
  <Dialog :open="show" @update:open="(value: any) => !value && handleClose()">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>{{ isEdit ? 'Edit User' : 'Create New User' }}</DialogTitle>
        <DialogDescription v-if="isEdit">
          Update user information
        </DialogDescription>
        <DialogDescription v-else>
          Add a new user to the system
        </DialogDescription>
      </DialogHeader>
      
      <UserEditForm 
        :show="show" 
        :primaryKey="primaryKey" 
        :isEdit="isEdit"
        @close="handleClose"
        @saved="handleSaved"
      />
    </DialogContent>
  </Dialog>
</template>
