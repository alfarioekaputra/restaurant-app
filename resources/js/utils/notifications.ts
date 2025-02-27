import Swal from 'sweetalert2';

/**
 * Show a success notification
 * @param message The message to display
 * @param title Optional title, defaults to 'Success'
 */
export const showSuccess = (message: string, title: string = 'Success') => {
  return Swal.fire({
    title: title,
    text: message,
    icon: 'success',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
  });
};

/**
 * Show an error notification
 * @param message The message to display
 * @param title Optional title, defaults to 'Error'
 */
export const showError = (message: string, title: string = 'Error') => {
  return Swal.fire({
    title: title,
    text: message,
    icon: 'error',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
  });
};

/**
 * Show an info notification
 * @param message The message to display
 * @param title Optional title, defaults to 'Information'
 */
export const showInfo = (message: string, title: string = 'Information') => {
  return Swal.fire({
    title: title,
    text: message,
    icon: 'info',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
  });
};

/**
 * Show a warning notification
 * @param message The message to display
 * @param title Optional title, defaults to 'Warning'
 */
export const showWarning = (message: string, title: string = 'Warning') => {
  return Swal.fire({
    title: title,
    text: message,
    icon: 'warning',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
  });
};

/**
 * Show a confirmation dialog
 * @param message The message to display
 * @param title Optional title, defaults to 'Confirm'
 * @param confirmButtonText Optional confirm button text, defaults to 'Yes'
 * @param cancelButtonText Optional cancel button text, defaults to 'No'
 */
export const showConfirm = (
  message: string, 
  title: string = 'Confirm', 
  confirmButtonText: string = 'Yes', 
  cancelButtonText: string = 'No'
) => {
  return Swal.fire({
    title: title,
    text: message,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: confirmButtonText,
    cancelButtonText: cancelButtonText
  });
};
