<template>
  <div
    class="flex flex-col gap-2 p-5 rounded bg-neutral-700 hover:bg-neutral-900 hover:cursor-pointer"
    @click="selectTask"
  >
    <div
      :class="`h-1 w-8 rounded-full ${
        priorityColor ? `bg-[${priorityColor}]` : ''
      }`"
    />
    <div>{{ task.title }}</div>
    <div class="text-neutral-400">
      {{ task.description }}
    </div>
    <div class="flex gap-2" v-if="task.tags && task.tags.length">
      <div
        class="p-1 px-3 rounded bg-neutral-600 text-sm"
        v-for="tag in task.tags || []"
        :key="tag.id"
      >
        {{ tag.name }}
      </div>
    </div>
    <div div class="flex gap-2">
      <span class="text-neutral-400 text-sm">F 3</span>
      <span class="text-neutral-400 text-sm" v-if="task.due_at">
        {{ task.due_at }}
      </span>
    </div>
    <div class="flex gap-2">
      <button
        class="w-[50%] p-1 rounded-full border-2 border-yellow-600 hover:bg-yellow-600 text-sm"
        @click.stop="archiveTask"
        v-if="!task.archived_at"
      >
        ARCHIVE
      </button>
      <button
        class="w-[50%] p-1 rounded-full border-2 border-green-800 hover:bg-green-800 text-sm"
        @click.stop="restoreTask"
        v-if="task.archived_at"
      >
        RESTORE
      </button>
      <button
        class="w-[50%] p-1 rounded-full border-2 border-green-800 hover:bg-green-800 text-sm"
        @click.stop="completeTask"
        v-if="!task.archived_at && !task.completed_at"
      >
        COMPLETE
      </button>
      <button
        class="w-[50%] p-1 rounded-full border-2 border-blue-700 hover:bg-blue-700 text-sm"
        @click.stop="todoTask"
        v-if="!task.archived_at && task.completed_at"
      >
        TODO
      </button>
    </div>
  </div>
</template>

<script>
import { computed } from "vue";
import TaskService from "../../services/task.service";

export default {
  setup(props, ctx) {
    const priorityColor = computed(() => {
      switch (props.task.priority) {
        case "low":
          return "#0096FF";
        case "normal":
          return "#50C878";
        case "high":
          return "#FFEA00";
        case "urgent":
          return "#EE4B2B";
      }
    });

    function selectTask() {
      ctx.emit("select", props.task);
    }

    async function completeTask() {
      try {
        const response = await TaskService.complete(props.task.id, {
          includes: "tags",
        });
        ctx.emit("complete", response.data.data);
      } catch (err) {
        console.error(err);
      }
    }

    async function todoTask() {
      try {
        const response = await TaskService.uncomplete(props.task.id, {
          includes: "tags",
        });
        ctx.emit("todo", response.data.data);
      } catch (err) {
        console.error(err);
      }
    }

    async function archiveTask() {
      try {
        const response = await TaskService.archive(props.task.id, {
          includes: "tags",
        });
        ctx.emit("archive", response.data.data);
      } catch (err) {
        console.error(err);
      }
    }

    async function restoreTask() {
      try {
        const response = await TaskService.restore(props.task.id, {
          includes: "tags",
        });
        ctx.emit("restore", response.data.data);
      } catch (err) {
        console.error(err);
      }
    }

    return {
      priorityColor,
      selectTask,
      completeTask,
      todoTask,
      archiveTask,
      restoreTask,
    };
  },
  props: {
    task: {
      type: Object,
      required: true,
    },
  },
  emits: ["select", "update", "complete", "todo", "archive", "restore"],
};
</script>
