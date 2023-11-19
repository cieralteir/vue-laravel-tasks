<template>
  <div>
    <BaseInput :label="label" :attrs="{ type: 'file' }" @input="uploadFile" />
    <ul class="mt-2" v-if="files">
      <li
        class="flex justify-between py-1"
        v-for="(file, index) in files"
        :key="file.id"
      >
        <!-- <button
          class="text-neutral-400 hover:text-neutral-50"
          @click.prevent="downloadFile(file)"
        >
          {{ file.name }}
        </button> -->
        <span class="text-neutral-400">
          <font-awesome-icon :icon="['fas', 'file']" /> {{ file.name }}
        </span>
        <div>
          <button
            class="p-1 px-3 rounded-md hover:bg-neutral-700 text-neutral-400 text-sm font-medium"
            @click.prevent="downloadFile(file)"
            v-if="customDownload"
          >
            Download
          </button>
          <button
            class="p-1 px-3 rounded-md hover:bg-neutral-700 text-red-400 text-sm font-medium"
            @click.prevent="deleteFile(file, index)"
          >
            Delete
          </button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import BaseInput from "./BaseInput.vue";
import { reactive, ref, watch } from "vue";

export default {
  setup(props, ctx) {
    const files = reactive(props.modelValue || []);
    watch(
      () => props.modelValue,
      () => {
        files.length = 0;
        files.push(...props.modelValue);
        console.log(props.modelValue);
      }
    );

    async function uploadFile(e) {
      try {
        if (e.target.files.length) {
          let file = e.target.files[0];
          if (props.customUpload) {
            await props.customUpload(file);
          } else {
            files.push(file);
            ctx.emit("update:modelValue", files);
          }
        }
      } catch (err) {
        console.error(err);
      }
    }

    function downloadFile(file) {
      if (props.customDownload) {
        props.customDownload(file);
      }
    }

    async function deleteFile(file, index) {
      try {
        if (props.customDelete) {
          await props.customDelete(file);
        } else {
          files.splice(index, 1);
          ctx.emit("update:modelValue", files);
        }
      } catch (err) {
        console.error(err);
      }
    }

    return {
      files,
      uploadFile,
      downloadFile,
      deleteFile,
    };
  },
  props: {
    label: {
      type: String,
      default: "Files",
    },
    modelValue: {
      type: Array,
      default: () => [],
    },
    customUpload: {
      type: Function,
    },
    customDownload: {
      type: Function,
    },
    customDelete: {
      type: Function,
    },
  },
  emits: ["update:modelValue"],
  components: {
    BaseInput,
  },
};
</script>
