<template>
  <component :is="layout">
    <RouterView />
  </component>
</template>

<script>
import { computed } from "vue";
import { RouterView, useRoute } from "vue-router";
import GuestLayout from "./layouts/GuestLayout.vue";
import AuthenticatedLayout from "./layouts/AuthenticatedLayout.vue";

export default {
  setup() {
    const route = useRoute();
    const layout = computed(() => {
      return route.meta?.public ? "GuestLayout" : "AuthenticatedLayout";
    });
    return {
      layout,
    };
  },
  components: {
    GuestLayout,
    AuthenticatedLayout,
  },
};
</script>
