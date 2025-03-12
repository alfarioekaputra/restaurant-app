<script setup lang="ts">
import { ref, watch, onMounted, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { showSuccess, showError } from '@/utils/notifications';
import { Category } from '@/types/category';

const props = defineProps<{
  categoryId?: number;
  show: boolean;
  isEdit?: boolean;
}>();

const emit = defineEmits(['close', 'saved']);
const loading = ref(false);
const category = ref<Category | null>(null);

const form = useForm({
  name: '',
});

// Define fetchUserData function before using it in watch callbacks
const fetchUserData = async () => {
  if (!props.categoryId) {
    // Reset form for new user
    form.name = '';
    return;
  }
  
  loading.value = true;
  try {
    const response = await axios.get(route('categories.edit', {id: props.categoryId}));
    category.value = response.data;
    
    // Populate form with user data
    form.name = category.value!.name;
    
  } catch (error) {
    console.error('Error fetching user data:', error);
    showError('Failed to load user data');
  } finally {
    loading.value = false;
  }
};

// Fetch user data when modal is shown
watch(() => props.show, async (newValue) => {
  if (newValue) {
    // Reset form errors when opening the modal
    form.clearErrors();
    await fetchUserData();
  }
}, { immediate: true });

// Fetch user data when categoryId changes
watch(() => props.categoryId, async (newValue) => {
  if (props.show) {
    await fetchUserData();
  }
});

const submit = () => {
  if (props.isEdit && props.categoryId) {
    // Update existing category
    
    form.put(route('categories.update', {id: props.categoryId}), {
      onSuccess: () => {
        showSuccess(`Category ${form.name} has been updated successfully`);
        emit('saved');
        emit('close');
      },
      onError: (errors) => {
        console.error('Form errors:', errors);
        showError('Failed to update category. Please check the form for errors.');
      }
    });
  } else {
    // Create new category
    form.post(route('categories.store'), {
      onSuccess: () => {
        showSuccess(`Category ${form.name} has been created successfully`);
        emit('saved');
        emit('close');
      },
      onError: (errors) => {
        console.error('Form errors:', errors);
        showError('Failed to create category. Please check the form for errors.');
      }
    });
  }
};
</script>

<template>
  <div v-if="loading" class="flex justify-center items-center p-8">
    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div>
  </div>
  
  <form v-else @submit.prevent="submit" class="space-y-4">
    <div class="space-y-2">
      <Label for="name">Name</Label>
      <Input id="name" v-model="form.name" :class="{ 'border-red-500': form.errors.name }" />
      <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
    </div>
    
    <div class="flex justify-end space-x-2">
      <Button type="button" variant="outline" @click="emit('close')">Cancel</Button>
      <Button type="submit" :disabled="form.processing">{{ props.isEdit ? 'Save Changes' : 'Create Category' }}</Button>
    </div>
  </form>
</template>
