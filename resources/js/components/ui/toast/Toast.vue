<script setup lang="ts">
import { cva } from 'class-variance-authority';
import { X } from 'lucide-vue-next';
import { computed } from 'vue';

type ToastVariant = 'default' | 'destructive' | 'success';

const toastVariants = cva(
  'group pointer-events-auto relative flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-6 pr-8 shadow-lg transition-all data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-80 data-[state=closed]:slide-out-to-right-full data-[state=open]:slide-in-from-top-full',
  {
    variants: {
      variant: {
        default: 'border bg-background text-foreground',
        destructive:
          'border-destructive bg-destructive text-destructive-foreground',
        success: 'border-green-500 bg-green-500 text-white',
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  }
);

const props = defineProps<{
  variant?: ToastVariant;
  class?: string;
}>();

const toastClass = computed(() => {
  return toastVariants({ variant: props.variant as ToastVariant }) + ' ' + (props.class || '');
});
</script>

<template>
  <div :class="toastClass">
    <slot />
    <slot name="close">
      <button
        class="absolute right-2 top-2 rounded-md p-1 text-foreground/50 opacity-0 transition-opacity hover:text-foreground focus:opacity-100 focus:outline-none focus:ring-2 group-hover:opacity-100"
        @click="$emit('close')"
      >
        <X class="h-4 w-4" />
      </button>
    </slot>
  </div>
</template>
