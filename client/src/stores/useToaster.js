import { reactive, ref } from "vue";
import { defineStore } from "pinia";

export const useToaster = defineStore("toaster", () => {
  const toasts = reactive([]);

  function toast(message, type) {
    toasts.push({
      message,
      type,
    });

    setTimeout(() => {
      toasts.splice(0, 1);
    }, 3000);
  }

  return {
    toasts,
    toast,
  };
});
