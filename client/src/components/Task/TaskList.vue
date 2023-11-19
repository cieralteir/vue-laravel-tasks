<template>
  <div class="flex flex-col h-full md:w-96 rounded bg-neutral-900">
    <div class="flex justify-between p-3 md:px-0">
      <h3 class="text font-medium">{{ title }}</h3>
    </div>
    <div class="flex-grow flex flex-col gap-2 w-[95vw] md:w-96 px-3 md:pl-0 pb-3 overflow-auto">
      <TaskListCard
        :task="task"
        @select="onTaskSelect"
        @update="onTaskUpdate"
        @complete="onTaskUpdate"
        @todo="onTaskUpdate"
        @archive="onTaskUpdate"
        @restore="onTaskUpdate"
        v-for="task in tasks"
        :key="task.id"
      />
    </div>
  </div>
</template>

<script>
import TaskListCard from "./TaskListCard.vue";

export default {
  setup(props, ctx) {
    function onTaskSelect(task) {
      ctx.emit("select-task", task);
    }

    function onTaskUpdate(task) {
      ctx.emit("update-task", task);
    }

    return {
      onTaskSelect,
      onTaskUpdate,
    };
  },
  props: {
    title: {
      type: String,
    },
    tasks: {
      type: Array,
      default: () => [],
    },
    color: {
      type: String,
    },
  },
  emits: ["select-task", "update-task"],
  components: {
    TaskListCard,
  },
};
</script>
