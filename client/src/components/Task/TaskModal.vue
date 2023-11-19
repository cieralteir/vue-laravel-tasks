<template>
  <BaseModal>
    <TaskDeleteModal
      :task="task"
      @delete="onDeleteTask"
      @close="taskDeleteModal = false"
      v-if="taskDeleteModal"
    />
    <div class="flex justify-between px-9">
      <h1 class="text-2xl font-medium">
        {{ task ? "UPDATE" : "CREATE" }} TASK
      </h1>
      <button
        class="p-2 px-3 rounded-md hover:bg-neutral-700 font-medium"
        @click="close"
      >
        CLOSE
      </button>
    </div>
    <form
      class="flex flex-col flex-grow overflow-auto"
      @submit.prevent="saveTask"
    >
      <div class="flex flex-col gap-4 md:max-h-[60vh] my-9 px-9 overflow-auto">
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
        <BaseFileInput
          v-model="form.files"
          :custom-upload="uploadFile"
          :custom-download="downloadFile"
          :custom-delete="deleteFile"
          v-if="task"
        />
        <BaseFileInput v-model="form.files" v-else />
      </div>
      <div class="flex gap-2 px-9">
        <button
          class="w-full p-3 rounded-md bg-red-900 hover:bg-neutral-700 font-medium"
          @click.prevent="taskDeleteModal = true"
          v-if="task"
        >
          DELETE
        </button>
        <button
          class="w-full p-3 rounded-md bg-neutral-700 hover:bg-neutral-600 font-medium"
          type="submit"
        >
          SAVE
        </button>
      </div>
    </form>
  </BaseModal>
</template>

<script>
import { reactive, ref } from "vue";
import moment from "moment";
import { useToaster } from "../../stores/useToaster";
import BaseModal from "../Base/BaseModal.vue";
import BaseInput from "../Base/BaseInput.vue";
import BaseSelect from "../Base/BaseSelect.vue";
import TaskDeleteModal from "./TaskDeleteModal.vue";
import TaskService from "../../services/task.service";
import BaseFileInput from "../Base/BaseFileInput.vue";
import FileService from "../../services/file.service";

export default {
  setup(props, ctx) {
    const toaster = useToaster();
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
      files: props.task?.files || [],
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

    async function saveTask() {
      try {
        const mode = props.task ? "update" : "create";
        const data = {};
        const formData = new FormData();
        Object.entries({
          ...form,
          tags: form.tags ? form.tags.split(",") : [],
        }).forEach(([key, value]) => {
          if (value == "") return;
          if (Array.isArray(value)) {
            value.forEach((v) => formData.append(`${key}[]`, v));
          } else {
            formData.append(key, value);
          }
          data[key] = value;
        });
        let request = null;
        if (mode == "create") {
          request = TaskService.create(formData);
        } else {
          request = TaskService.update(props.task.id, data, {
            includes: "tags,files",
          });
        }
        const response = await request;
        toaster.toast("Task has been saved.", "success");
        ctx.emit(mode, response.data.data);
      } catch (err) {
        console.error(err);
        errorBag.errors = err?.response?.data?.errors || {};
      }
    }

    const taskDeleteModal = ref(false);
    function onDeleteTask() {
      ctx.emit("delete", props.task);
      taskDeleteModal.value = false;
    }

    async function uploadFile(file) {
      try {
        const formData = new FormData();
        formData.append("file", file);
        const response = await TaskService.createFile(props.task.id, formData);
        form.files = response.data.data.files;
        toaster.toast("File has been uploaded", "success");
        ctx.emit("update-files", {
          ...props.task,
          files: response.data.data.files,
        });
      } catch (err) {
        console.error(err);
        throw err;
      }
    }
    async function downloadFile(file) {
      try {
        const response = await FileService.read(file.id);
        const href = window.URL.createObjectURL(response.data);
        const a = document.createElement("a");
        a.href = href;
        a.download = file.name;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(href);
      } catch (err) {
        console.error(err);
      }
    }
    async function deleteFile(file) {
      try {
        await FileService.delete(file.id);
        // const fileIndex = form.files.findIndex((v) => v.id == file.id);
        // if (fileIndex > -1) {
        //   form.files.splice(fileIndex, 1);
        // }
        form.files = form.files.filter((v) => v.id != file.id);
        toaster.toast("File has been deleted.", "success");
        ctx.emit("update-files", {
          ...props.task,
          files: form.files,
        });
      } catch (err) {
        console.error(err);
        throw err;
      }
    }

    function close() {
      ctx.emit("close");
    }

    return {
      form,
      errorBag,
      minDate,
      priorities,
      saveTask,
      taskDeleteModal,
      onDeleteTask,
      uploadFile,
      downloadFile,
      deleteFile,
      close,
    };
  },
  props: {
    task: {
      type: Object,
    },
  },
  emits: ["create", "update", "delete", "update-files", "close"],
  components: {
    BaseModal,
    BaseInput,
    BaseSelect,
    BaseFileInput,
    TaskDeleteModal,
  },
};
</script>
