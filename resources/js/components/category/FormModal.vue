<script setup lang="ts">
import { ref, watch } from 'vue';
import { 
  Dialog, 
  DialogContent, 
  DialogHeader, 
  DialogTitle,
  DialogDescription
} from '@/components/ui/dialog';
import CategoryForm from './_form.vue';

const props = defineProps<{
  show: boolean;
  categoryId?: number;
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
        <DialogTitle>{{ isEdit ? 'Edit Category' : 'Create New Category' }}</DialogTitle>
        <DialogDescription v-if="isEdit">
          Update category information
        </DialogDescription>
        <DialogDescription v-else>
          Add a new category to the system
        </DialogDescription>
      </DialogHeader>
      
      <CategoryForm 
        :show="show" 
        :categoryId="categoryId" 
        :isEdit="isEdit"
        @close="handleClose"
        @saved="handleSaved"
      />
    </DialogContent>
  </Dialog>
</template>
