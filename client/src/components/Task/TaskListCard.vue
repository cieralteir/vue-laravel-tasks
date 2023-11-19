<template>
  <div
    class="group flex flex-col gap-2 p-5 rounded bg-neutral-800 hover:bg-neutral-700 hover:cursor-pointer"
    @click="selectTask"
  >
    <div
      :class="`h-1 w-8 rounded-full ${
        priorityColor ? `bg-[${priorityColor}]` : ''
      }`"
    />
    <div class="text-lg">{{ task.title }}</div>
    <div class="text-neutral-400">
      {{ task.description }}
    </div>
    <div class="flex gap-2" v-if="task.tags && task.tags.length">
      <div
        class="p-1 px-3 rounded bg-neutral-700 group-hover:bg-neutral-800 text-sm"
        v-for="tag in task.tags || []"
        :key="tag.id"
      >
        {{ tag.name }}
      </div>
    </div>
    <div div class="flex gap-2">
      <span class="text-neutral-400">
        <font-awesome-icon :icon="['fas', 'file']" />
        {{ (task.files || []).length }}
      </span>
      <span class="text-neutral-400" v-if="dueDateFormatted">
        <font-awesome-icon :icon="['fas', 'calendar-day']" />
        {{ dueDateFormatted }}
      </span>
    </div>
    <div class="flex gap-2 mt-2">
      <button
        class="p-2 px-3 rounded-md hover:bg-neutral-800 font-medium"
        @click.stop="archiveTask"
        v-if="!task.archived_at"
      >
        ARCHIVE
      </button>
      <button
        class="p-2 px-3 rounded-md hover:bg-neutral-800 font-medium"
        @click.stop="restoreTask"
        v-if="task.archived_at"
      >
        RESTORE
      </button>
      <button
        class="p-2 px-3 rounded-md hover:bg-neutral-800 font-medium"
        @click.stop="completeTask"
        v-if="!task.archived_at && !task.completed_at"
      >
        COMPLETE
      </button>
      <button
        class="p-2 px-3 rounded-md hover:bg-neutral-800 font-medium"
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
import { useToaster } from "../../stores/useToaster";
import TaskService from "../../services/task.service";
import moment from "moment";

/* All supported classes for color props
  bg-[#0096FF] bg-[#50C878]
  bg-[#FFEA00] bg-[#EE4B2B]
  */
export default {
  setup(props, ctx) {
    const toaster = useToaster();
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
    const dueDateFormatted = computed(() =>
      props.task?.due_at ? moment(props.task.due_at).format("YYYY-MM-DD") : ""
    );

    function selectTask() {
      ctx.emit("select", props.task);
    }

    async function completeTask() {
      try {
        const response = await TaskService.complete(props.task.id, {
          includes: "tags",
        });
        toaster.toast("Task has been marked as completed.", "success");
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
        toaster.toast("Task has been marked as todo.", "success");
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
        toaster.toast("Task has been archived.", "success");
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
        toaster.toast("Task has been restored.", "success");
        ctx.emit("restore", response.data.data);
      } catch (err) {
        console.error(err);
      }
    }

    return {
      priorityColor,
      dueDateFormatted,
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
