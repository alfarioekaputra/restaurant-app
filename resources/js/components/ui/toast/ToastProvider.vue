<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import Toast from './Toast.vue';

// Define toast type
type ToastVariant = 'default' | 'destructive' | 'success';

// Define toast interface
interface ToastMessage {
  id: number;
  message: string;
  type: ToastVariant;
  duration?: number;
}

const toasts = ref<ToastMessage[]>([]);
let toastId = 0;

// Watch for flash messages from Inertia
const page = usePage();

watch(() => page.props.flash, (flash: any) => {
  if (flash.success) {
    addToast(flash.success, 'success');
  }
  if (flash.error) {
    addToast(flash.error, 'destructive');
  }
}, { deep: true, immediate: true });

// Add toast function
const addToast = (message: string, type: ToastVariant = 'default', duration = 5000) => {
  const id = toastId++;
  toasts.value.push({ id, message, type, duration });
  
  // Auto remove toast after duration
  if (duration > 0) {
    setTimeout(() => {
      removeToast(id);
    }, duration);
  }
};

// Remove toast function
const removeToast = (id: number) => {
  const index = toasts.value.findIndex(toast => toast.id === id);
  if (index !== -1) {
    toasts.value.splice(index, 1);
  }
};

// Map toast type to variant
const getVariant = (type: ToastVariant): ToastVariant => {
  return type;
};
</script>

<template>
  <div 
    aria-live="assertive" 
    class="pointer-events-none fixed inset-0 z-50 flex flex-col items-end gap-4 px-4 py-6 sm:p-6"
  >
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
      <transition-group 
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <Toast 
          v-for="toast in toasts" 
          :key="toast.id"
          :variant="getVariant(toast.type)"
          class="w-full max-w-md"
          @close="removeToast(toast.id)"
        >
          <div class="flex">
            <div class="flex-1">{{ toast.message }}</div>
          </div>
        </Toast>
      </transition-group>
    </div>
  </div>
</template>
