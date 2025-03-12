<script setup lang="ts">
import { ref, watch, onMounted, computed } from 'vue';
import { User, Role } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { showSuccess, showError } from '@/utils/notifications';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';

const props = defineProps<{
  primaryKey?: number;
  show: boolean;
  isEdit?: boolean;
}>();

const emit = defineEmits(['close', 'saved']);
const loading = ref(false);
const user = ref<User | null>(null);

// Get available roles from the page props
const roles = computed(() => {
  return usePage().props.roles as Role[] || [];
});

const form = useForm({
  name: '',
  email: '',
  password: '',
  role_id: '',
});

// Define fetchUserData function before using it in watch callbacks
const fetchUserData = async () => {
  if (!props.primaryKey) {
    // Reset form for new user
    form.name = '';
    form.email = '';
    form.password = '';
    form.role_id = '';
    return;
  }
  
  loading.value = true;
  try {
    const response = await axios.get(route('users.edit', {id: props.primaryKey}));
    user.value = response.data;
    
    // Populate form with user data
    form.name = user.value!.name;
    form.email = user.value!.email;
    form.password = ''; // Clear password field
    
    // Set role if user has one
    if (user.value!.roles && user.value!.roles.length > 0) {
      const roleId = user.value!.roles[0].id;
      const role = roles.value.find(role => role.id === roleId);
      if (role) {
        form.role_id = String(roleId);
      } else {
        form.role_id = '';
      }
    } else {
      form.role_id = '';
    }
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

// Fetch user data when primaryKey changes
watch(() => props.primaryKey, async (newValue) => {
  if (props.show) {
    await fetchUserData();
  }
});

const submit = () => {
  if (props.isEdit && props.primaryKey) {
    // Update existing user
    // If password is empty, remove it from the form data
    if (!form.password) {
      form.delete('password');
    }
    
    form.put(route('users.update', {id: props.primaryKey}), {
      onSuccess: () => {
        showSuccess(`User ${form.name} has been updated successfully`);
        emit('saved');
        emit('close');
      },
      onError: (errors) => {
        console.error('Form errors:', errors);
        showError('Failed to update user. Please check the form for errors.');
      }
    });
  } else {
    // Create new user
    form.post(route('users.store'), {
      onSuccess: () => {
        showSuccess(`User ${form.name} has been created successfully`);
        emit('saved');
        emit('close');
      },
      onError: (errors) => {
        console.error('Form errors:', errors);
        showError('Failed to create user. Please check the form for errors.');
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
      <Input id="name" v-model="form.name" required :class="{ 'border-red-500': form.errors.name }" />
      <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
    </div>
    
    <div class="space-y-2">
      <Label for="email">Email</Label>
      <Input id="email" v-model="form.email" type="email" required :class="{ 'border-red-500': form.errors.email }" />
      <div v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</div>
    </div>
    
    <div class="space-y-2">
      <Label for="password">Password</Label>
      <Input id="password" v-model="form.password" type="password" :required="!props.isEdit" :class="{ 'border-red-500': form.errors.password }" />
      <div v-if="props.isEdit" class="text-gray-500 text-xs">Leave blank to keep current password</div>
      <div v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</div>
    </div>
    
    <div class="space-y-2">
      <Label for="role">Role</Label>
      <Select v-model="form.role_id" required>
        <SelectTrigger :class="{ 'border-red-500': form.errors.role_id }">
          <SelectValue placeholder="Select a role" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem v-for="role in roles" :key="role.id" :value="String(role.id)">
            {{ role.name }}
          </SelectItem>
        </SelectContent>
      </Select>
      <div v-if="form.errors.role_id" class="text-red-500 text-sm">{{ form.errors.role_id }}</div>
    </div>

    <div class="flex justify-end space-x-2">
      <Button type="button" variant="outline" @click="emit('close')">Cancel</Button>
      <Button type="submit" :disabled="form.processing">{{ props.isEdit ? 'Save Changes' : 'Create User' }}</Button>
    </div>
  </form>
</template>
