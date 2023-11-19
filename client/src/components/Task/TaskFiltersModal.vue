<template>
  <BaseModal>
    <div class="flex justify-between px-9">
      <h1 class="text-2xl font-medium">FILTER TASKS</h1>
      <button
        class="p-2 px-3 rounded-md hover:bg-neutral-700 font-medium"
        @click="close"
      >
        CLOSE
      </button>
    </div>
    <div class="flex flex-col gap-4 h-[60vh] my-9 px-9 overflow-auto">
      <BaseInput v-model="form.title" label="Title" />
      <BaseInput
        v-model="form.description"
        label="Description"
        :attrs="{ type: 'textarea' }"
      />
      <BaseSelect
        v-model="form.priority"
        label="Priority"
        :options="priorities"
      />
      <BaseInput
        v-model="form.due_at_start"
        label="Due Date Start"
        :attrs="{ type: 'date' }"
      />
      <BaseInput
        v-model="form.due_at_end"
        label="Due Date End"
        :attrs="{ type: 'date' }"
      />
      <BaseInput
        v-model="form.completed_at_start"
        label="Completed Date Start"
        :attrs="{ type: 'date' }"
      />
      <BaseInput
        v-model="form.completed_at_end"
        label="Completed Date End"
        :attrs="{ type: 'date' }"
      />
      <BaseInput
        v-model="form.archived_at_start"
        label="Archived Date Start"
        :attrs="{ type: 'date' }"
      />
      <BaseInput
        v-model="form.archived_at_end"
        label="Archived Date End"
        :attrs="{ type: 'date' }"
      />
    </div>
    <div class="flex gap-2 px-9">
      <button
        class="w-full p-3 rounded-md bg-neutral-700 font-medium"
        @click="filter"
      >
        FILTER
      </button>
    </div>
  </BaseModal>
</template>

<script>
import { reactive } from "vue";
import BaseModal from "../Base/BaseModal.vue";
import BaseInput from "../Base/BaseInput.vue";
import BaseSelect from "../Base/BaseSelect.vue";

export default {
  setup(props, ctx) {
    console.log(props.filters);
    const form = reactive({
      title: props.filters?.title,
      description: props.filters?.description,
      priority: props.filters?.priority,
      due_at_start: props.filters?.due_at_start,
      due_at_end: props.filters?.due_at_end,
      completed_at_start: props.filters?.completed_at_start,
      completed_at_end: props.filters?.completed_at_end,
      archived_at_start: props.filters?.archived_at_start,
      archived_at_end: props.filters?.archived_at_end,
    });
    const priorities = [
      {
        value: "",
        label: "All",
      },
      {
        value: "low",
        label: "Low",
      },
      {
        value: "normal",
        label: "Normal",
      },
      {
        value: "high",
        label: "High",
      },
      {
        value: "urgent",
        label: "Urgent",
      },
    ];

    function filter() {
      ctx.emit("update:filters", form);
      ctx.emit("filter", form);
      close();
    }

    function close() {
      ctx.emit("close");
    }

    return {
      form,
      priorities,
      filter,
      close,
    };
  },
  props: {
    filters: {
      type: Object,
    },
  },
  emits: ["filter", "close", "update:filters"],
  components: {
    BaseModal,
    BaseInput,
    BaseSelect,
  },
};
</script>
