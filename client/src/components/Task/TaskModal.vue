<template>
  <BaseModal>
    <div
      class="relative top-20 w-128 p-9 mx-auto rounded-md bg-neutral-800 shadow-lg"
    >
      <TaskDeleteModal
        :task="task"
        @delete="onDeleteTask"
        @close="taskDeleteModal = false"
        v-if="taskDeleteModal"
      />
      <div class="flex justify-between">
        <h1 class="text-2xl font-medium">
          {{ task ? "UPDATE" : "CREATE" }} TASK
        </h1>
        <button @click="close">CLOSE</button>
      </div>
      <form @submit.prevent="saveTask">
        <div class="flex flex-col gap-4 my-9">
          <BaseInput
            v-model="form.title"
            label="Title *"
            :error="errorBag.errors.title ? errorBag.errors.title[0] : ''"
          />
          <BaseInput
            v-model="form.description"
            label="Description *"
            :attrs="{ type: 'textarea' }"
            :error="
              errorBag.errors.description ? errorBag.errors.description[0] : ''
            "
          />
          <BaseInput
            v-model="form.due_at"
            label="Due Date"
            :attrs="{ type: 'date', min: minDate }"
            :error="errorBag.errors.due_at ? errorBag.errors.due_at[0] : ''"
          />
          <BaseSelect
            v-model="form.priority"
            label="Priority"
            :options="priorities"
            :error="errorBag.errors.priority ? errorBag.errors.priority[0] : ''"
          />
          <BaseInput v-model="form.tags" label="Tags" />
        </div>
        <div class="flex gap-2">
          <button
            class="w-full p-4 rounded-md bg-red-900 hover:bg-neutral-700"
            @click.prevent="taskDeleteModal = true"
            v-if="task"
          >
            DELETE
          </button>
          <button
            class="w-full p-4 rounded-md bg-neutral-900 hover:bg-neutral-700"
            type="submit"
          >
            SAVE
          </button>
        </div>
      </form>
    </div>
  </BaseModal>
</template>

<script>
import { reactive, ref } from "vue";
import moment from "moment";
import BaseModal from "../Base/BaseModal.vue";
import BaseInput from "../Base/BaseInput.vue";
import BaseSelect from "../Base/BaseSelect.vue";
import TaskDeleteModal from "./TaskDeleteModal.vue";
import TaskService from "../../services/task.service";

export default {
  setup(props, ctx) {
    const form = reactive({
      title: props.task?.title || "",
      description: props.task?.description || "",
      due_at: props.task?.due_at
        ? moment(props.task.due_at).format("YYYY-MM-DD")
        : "",
      priority: props.task?.priority || "",
      tags: props.task?.tags
        ? props.task.tags.map((v) => v.name).join(",")
        : "",
    });
    const errorBag = reactive({ errors: {} });

    const minDate = moment().format("YYYY-MM-DD");
    const priorities = [
      {
        value: "",
        label: "Not Applicable",
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

    const taskDeleteModal = ref(false);

    async function saveTask() {
      try {
        const mode = props.task ? "update" : "create";
        const data = {};
        Object.entries({
          ...form,
          tags: form.tags ? form.tags.split(",") : [],
        }).forEach(([key, value]) => {
          if (value !== "") {
            data[key] = value;
          }
        });
        let request = null;
        if (mode == "create") {
          request = TaskService.create(data);
        } else {
          request = TaskService.update(props.task.id, data, {
            includes: "tags",
          });
        }
        const response = await request;
        ctx.emit(mode, response.data.data);
      } catch (err) {
        console.error(err);
        errorBag.errors = err?.response?.data?.errors || {};
      }
    }

    function onDeleteTask() {
      ctx.emit("delete", props.task);
      taskDeleteModal.value = false;
    }

    function close() {
      ctx.emit("close");
    }

    return {
      form,
      errorBag,
      minDate,
      priorities,
      taskDeleteModal,
      saveTask,
      onDeleteTask,
      close,
    };
  },
  props: {
    task: {
      type: Object,
    },
  },
  emits: ["create", "update", "delete", "close"],
  components: {
    BaseModal,
    BaseInput,
    BaseSelect,
    TaskDeleteModal,
  },
};
</script>
