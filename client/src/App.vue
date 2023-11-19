<template>
  <component :is="layout">
    <RouterView />
  </component>
  <TheToaster
    :message="toast.message"
    :type="toast.type"
    v-for="toast in toaster.toasts"
  />
</template>

<script>
import { computed } from "vue";
import { RouterView, useRoute } from "vue-router";
import GuestLayout from "./layouts/GuestLayout.vue";
import AuthenticatedLayout from "./layouts/AuthenticatedLayout.vue";
import TheToaster from "./components/TheToaster.vue";
import { useToaster } from "./stores/useToaster";

export default {
  setup() {
    const route = useRoute();
    const toaster = useToaster();
    const layout = computed(() => {
      return route.meta?.public ? "GuestLayout" : "AuthenticatedLayout";
    });

    return {
      toaster,
      layout,
    };
  },
  components: {
    GuestLayout,
    AuthenticatedLayout,
    TheToaster,
  },
};
</script>
