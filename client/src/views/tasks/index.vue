<template>
  <div class="flex justify-center h-full w-full p-5">
    <TaskFiltersModal
      v-model:filters="filters"
      @filter="onFilterTasks"
      @close="taskFilterModal = false"
      v-if="taskFilterModal"
    />
    <TaskModal
      :task="taskSelected"
      @create="onTaskCreate"
      @update="onTaskUpdate"
      @delete="onTaskDelete"
      @close="taskModal = false"
      v-if="taskModal"
    />
    <div class="flex flex-col gap-5">
      <div class="flex justify-between">
        <button class="" @click="() => openTaskModal()">ADD TASK</button>
        <div class="flex gap-5">
          <button class="" @click="taskFilterModal = true">FILTER</button>
          <button class="">SORT</button>
        </div>
      </div>
      <div class="flex gap-5">
        <TaskList
          title="TODO"
          :tasks="tasksTodo"
          @select-task="onTaskSelect"
          @update-task="onTaskUpdate"
        />
        <TaskList
          title="COMPLETED"
          :tasks="tasksCompleted"
          @select-task="onTaskSelect"
          @update-task="onTaskUpdate"
        />
        <TaskList
          title="ARCHIVED"
          :tasks="tasksArchived"
          @select-task="onTaskSelect"
          @update-task="onTaskUpdate"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { computed, nextTick, onMounted, reactive, ref } from "vue";
import TaskList from "../../components/Task/TaskList.vue";
import TaskService from "../../services/task.service";
import TaskModal from "../../components/Task/TaskModal.vue";
import TaskFiltersModal from "../../components/Task/TaskFiltersModal.vue";

export default {
  setup() {
    const taskSelected = ref(null);
    const tasks = reactive([]);
    const tasksActive = computed(() =>
      tasks.filter((task) => !task.archived_at)
    );
    const tasksTodo = computed(() =>
      tasksActive.value.filter((task) => !task.completed_at)
    );
    const tasksCompleted = computed(() =>
      tasksActive.value.filter((task) => task.completed_at)
    );
    const tasksArchived = computed(() =>
      tasks.filter((task) => task.archived_at)
    );
    async function fetchTasks(filters) {
      try {
        const response = await TaskService.list({
          ...filters,
          includes: "tags",
        });
        tasks.length = 0;
        tasks.push(...(response.data?.data || []));
      } catch (err) {
        console.error(err);
      }
    }
    function onTaskSelect(task) {
      openTaskModal(task);
    }
    function onTaskCreate() {
      fetchTasks();
      taskModal.value = false;
    }
    function onTaskUpdate(task) {
      const taskIndex = tasks.findIndex((v) => v.id == task.id);
      tasks[taskIndex] = task;
      taskModal.value = false;
    }
    function onTaskDelete(task) {
      const taskIndex = tasks.findIndex((v) => v.id == task.id);
      tasks.splice(taskIndex, 1);
      taskModal.value = false;
    }

    const filters = reactive({});
    const taskFilterModal = ref(false);
    function onFilterTasks(filters) {
      console.log(filters);
      fetchTasks(filters);
    }

    const taskModal = ref(false);
    function openTaskModal(task = null) {
      taskSelected.value = task;
      taskModal.value = true;
    }

    onMounted(() => {
      fetchTasks();
    });

    return {
      taskSelected,
      tasksTodo,
      tasksCompleted,
      tasksArchived,
      onTaskSelect,
      onTaskCreate,
      onTaskUpdate,
      onTaskDelete,
      filters,
      taskFilterModal,
      onFilterTasks,
      taskModal,
      openTaskModal,
    };
  },
  components: {
    TaskList,
    TaskModal,
    TaskFiltersModal,
  },
};
</script>
