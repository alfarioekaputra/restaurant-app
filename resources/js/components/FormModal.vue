<script setup lang="ts">
import { ref, watch, defineAsyncComponent } from 'vue';
import { 
  Dialog, 
  DialogContent, 
  DialogHeader, 
  DialogTitle,
  DialogDescription,
  DialogScrollContent
} from '@/components/ui/dialog';

const props = defineProps<{
  show: boolean;
  isEdit?: boolean;
  title: string;
}>();

const emit = defineEmits(['close', 'saved']);

const handleClose = () => {
  emit('close');
};

</script>

<template>
  <Dialog :open="show" @update:open="(value: any) => !value && handleClose()">
    <DialogScrollContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>{{ isEdit ? 'Edit ' + props.title : 'Create New ' + props.title }}</DialogTitle>
        <DialogDescription v-if="isEdit">
          Update {{ props.title }} information
        </DialogDescription>
        <DialogDescription v-else>
          Add a new {{ props.title }} to the system
        </DialogDescription>
      </DialogHeader>
      <slot />
    </DialogScrollContent>
  </Dialog>
</template>
