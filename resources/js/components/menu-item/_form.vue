<script setup lang="ts">
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { router, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { showSuccess, showError } from '@/utils/notifications';
import Switch from '../ui/switch/Switch.vue';
import Textarea from '../ui/textarea/Textarea.vue';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { MenuItem } from '@/types/menuItem';
import { Category } from '@/types/category';

const props = defineProps<{
  primaryKey?: number;
  show: boolean;
  isEdit?: boolean;
}>();

const emit = defineEmits(['close', 'saved']);
const loading = ref(false);
const menuItem = ref<MenuItem | null>(null);
const categories = ref<Category[]>([]);

const form = useForm({
  name: '',
  price: 0,
  image_url: '',
  is_available: true as boolean,
  description: '',
  category_id: 0
});

// Define fetchUserData function before using it in watch callbacks
const fetchUserData = async () => {
  if (!props.primaryKey) {
    // Reset form for new user
    form.name = '';
    return;
  }
  
  loading.value = true;
  try {
    const response = await axios.get(route('menu-items.edit', {id: props.primaryKey}));
    menuItem.value = response.data;
    categories.value = response.data.categories;
    
    // Populate form with user data
    form.name = menuItem.value!.name;
    form.price = menuItem.value!.price;
    form.image_url = menuItem.value!.image_url;
    form.is_available = menuItem.value!.is_available;
    form.description = menuItem.value!.description;
    form.category_id = menuItem.value!.category_id;
    
  } catch (error) {
    console.error('Error fetching data:', error);
    showError('Failed to load data');
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

// Fetch user data when primaryKey changes
watch(() => props.primaryKey, async (newValue) => {
  if (props.show) {
    await fetchUserData();
  }
});

const submit = () => {
  if (props.isEdit && props.primaryKey) {
    // Update existing menu item
    
    router.post(route('menu-items.update', {id: props.primaryKey}), {
      _method: 'put',
      category_id: form.category_id,
      name: form.name,
      price: form.price,
      image_url: form.image_url,
      is_available: form.is_available,
      description: form.description
    }, {
      onSuccess: () => {
        showSuccess(`Menu Item ${form.name} has been updated successfully`);
        emit('saved');
        emit('close');
      },
      onError: (errors) => {
        console.error('Form errors:', errors);
        showError('Failed to update menu item. Please check the form for errors.');
      }
    });
  } else {
    // Create new menu item
    form.post(route('menu-items.store'), {
      onSuccess: () => {
        showSuccess(`Menu Item ${form.name} has been created successfully`);
        emit('saved');
        emit('close');
      },
      onError: (errors) => {
        console.error('Form errors:', errors);
        showError('Failed to create menu item. Please check the form for errors.');
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
      <Label for="category_id">Category <span class="text-red-500">*</span></Label>
      <Select v-model="form.category_id" class="mt-2">
        <SelectTrigger>
          <SelectValue placeholder="Select a category" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </SelectItem>
        </SelectContent>
      </Select>
    </div>
    <div class="space-y-2">
      <Label for="name">Name <span class="text-red-500">*</span></Label>
      <Input id="name" v-model="form.name" required :class="{ 'border-red-500': form.errors.name }" />
      <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
    </div>
    <div class="space-y-2">
      <Label for="price">Price <span class="text-red-500">*</span></Label>
      <Input id="price" v-model="form.price" type="number" required :class="{ 'border-red-500': form.errors.price }" />
      <div v-if="form.errors.price" class="text-red-500 text-sm">{{ form.errors.price }}</div>
    </div>
    <div class="space-y-2">
      <Label for="image_url">Image <span class="text-red-500">*</span></Label>
      <Input id="image_url" type="file" @input="form.image_url = $event.target.files[0]" :required="!form.image_url" :class="{ 'border-red-500': form.errors.image_url }" />
      <img v-if="form.image_url" :src="form.image_url" class="mt-2" />
      <progress v-if="form.progress" :value="form.progress.percentage" max="100">
        {{ form.progress.percentage }}%
      </progress>
      <div v-if="form.errors.image_url" class="text-red-500 text-sm">{{ form.errors.image_url }}</div>
    </div>
    <div class="space-y-2">
      <Label for="description">Description</Label>
      <Textarea id="description" v-model="form.description" />
      <div v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description }}</div>
    </div>
    <div class="space-y-2">
      <Label for="is_available" class="mr-2">Is Available</Label>
      <Switch id="is_available" v-model="form.is_available" />
      <div v-if="form.errors.is_available" class="text-red-500 text-sm">{{ form.errors.is_available }}</div>
    </div>

    <div class="flex justify-end space-x-2">
      <Button type="button" variant="outline" @click="emit('close')">Cancel</Button>
      <Button type="submit" :disabled="form.processing">{{ props.isEdit ? 'Save Changes' : 'Create Menu Item' }}</Button>
    </div>
  </form>
</template>
