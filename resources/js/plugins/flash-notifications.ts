import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { showSuccess, showError } from '@/utils/notifications';

export default {
  install: (app: any) => {
    const page = usePage();

    // Watch for flash messages from Inertia
    watch(() => page.props?.flash, (flash: any) => {
      if (flash && flash.success) {
        showSuccess(flash.success);
      }
      if (flash && flash.error) {
        showError(flash.error);
      }
    }, { deep: true, immediate: true });
  }
};
