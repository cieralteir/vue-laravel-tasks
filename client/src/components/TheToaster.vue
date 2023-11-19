<template>
  <div
    class="fixed inset-x-0 md:inset-x-auto md:right-5 bottom-0 md:bottom-5 z-50"
  >
    <div :class="toastClass">
      <font-awesome-icon
        :icon="['fas', 'circle-check']"
        v-if="type == 'success'"
      />
      <font-awesome-icon
        :icon="['fas', 'circle-xmark']"
        v-if="type == 'error'"
      />
      {{ message }}
    </div>
  </div>
</template>

<script>
import { computed } from "vue";

export default {
  setup(props) {
    const toastClass = computed(() => {
      let bgColor = "bg-neutral-800";
      switch (props.type) {
        case "success":
          bgColor = "bg-green-500";
          break;
        case "error":
          bgColor = "bg-red-500";
          break;
      }
      return `px-5 py-3 rounded ${bgColor} shadow-md`;
    });
    return {
      toastClass,
    };
  },
  props: {
    message: {
      type: String,
    },
    type: {
      type: String,
    },
  },
};
</script>
