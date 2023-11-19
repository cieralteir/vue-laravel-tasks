<template>
  <BaseModal>
    <div class="px-9">
      <p class="text-2xl mb-3">Are you sure?</p>
      <p class="mb-5 text-neutral-400">
        This will delete the task permanently. You cannot undo this.
      </p>
      <div class="flex gap-2">
        <button
          class="w-full p-4 rounded-md bg-neutral-700 hover:bg-neutral-600 font-medium"
          @click.prevent="close"
        >
          CANCEL
        </button>
        <button
          class="w-full p-4 rounded-md bg-red-900 hover:bg-neutral-600 font-medium"
          @click="deleteTask"
        >
          CONFIRM
        </button>
      </div>
    </div>
  </BaseModal>
</template>

<script>
import { useToaster } from "../../stores/useToaster";
import BaseModal from "../Base/BaseModal.vue";
import TaskService from "../../services/task.service";

export default {
  setup(props, ctx) {
    const toaster = useToaster();
    async function deleteTask() {
      try {
        await TaskService.delete(props.task.id);
        toaster.toast("Task has been deleted.", "success");
        ctx.emit("delete", props.task);
      } catch (err) {
        console.error(err);
      }
    }

    function close() {
      ctx.emit("close");
    }

    return {
      deleteTask,
      close,
    };
  },
  props: {
    task: {
      type: Object,
    },
  },
  emits: ["delete", "close"],
  components: {
    BaseModal,
  },
};
</script>
