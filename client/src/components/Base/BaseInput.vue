<template>
  <div>
    <label class="block mb-2">{{ label }}</label>
    <input
      v-model="value"
      class="w-full py-2 px-3 rounded border text-neutral-900 outline-none"
      v-bind="attrs"
      @input="onInput"
    />
    <label class="block mt-2 text-sm text-red-500" v-if="error">
      {{ error }}
    </label>
  </div>
</template>

<script>
import { computed } from "vue";

export default {
  setup(props, ctx) {
    const value = computed({
      get() {
        return props.modelValue;
      },
      set(value) {
        ctx.emit("update:modelValue", value);
      },
    });

    function onInput(e) {
      ctx.emit("input", e);
    }

    return {
      value,
      onInput,
    };
  },
  props: {
    modelValue: {
      type: String,
    },
    label: {
      type: String,
    },
    error: {
      type: String,
    },
    attrs: {
      type: Object,
      default: () => {},
    },
  },
  emits: ["update:modelValue", "input"],
};
</script>
