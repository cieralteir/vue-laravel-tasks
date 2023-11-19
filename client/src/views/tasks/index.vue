<template>
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
    @update-files="onTaskFilesUpdate"
    @close="taskModal = false"
    v-if="taskModal"
  />
  <div class="flex justify-between">
    <button
      class="p-2 px-3 rounded-md hover:bg-neutral-700 font-medium"
      @click="() => openTaskModal()"
    >
      <font-awesome-icon :icon="['fas', 'plus']" /> ADD TASK
    </button>
    <div class="flex gap-5">
      <button
        class="p-2 px-3 rounded-md hover:bg-neutral-700 font-medium"
        @click="taskFilterModal = true"
      >
        <font-awesome-icon :icon="['fas', 'filter']" /> FILTER
      </button>
      <div class="group dropdown inline-block relative">
        <button class="p-2 px-3 rounded-md hover:bg-neutral-700 font-medium">
          <font-awesome-icon :icon="['fas', 'arrow-down-wide-short']" /> SORT
        </button>
        <ul
          class="absolute hidden group-hover:block left-auto right-0 w-[200px] pt-1 shadow-lg"
        >
          <li v-for="sort in sortColumns" :key="sort.value">
            <a
              :class="`${
                sort.value == orderBy ? 'bg-neutral-700' : 'bg-neutral-800'
              } hover:bg-neutral-700 py-2 px-4 block whitespace-no-wrap`"
              href="#"
              @click="sortTasks(sort.value)"
            >
              {{ sort.label }}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="flex flex-grow flex-nowrap gap-5 px-3 overflow-auto">
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
</template>

<script>
import { computed, onMounted, reactive, ref } from "vue";
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
    async function fetchTasks() {
      try {
        const response = await TaskService.list({
          ...filters,
          order_by: orderBy.value || undefined,
          includes: "tags,files",
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
    function onTaskFilesUpdate(task) {
      const taskIndex = tasks.findIndex((v) => v.id == task.id);
      tasks[taskIndex] = task;
    }

    const filters = reactive({});
    const taskFilterModal = ref(false);
    function onFilterTasks(filters) {
      fetchTasks(filters);
    }

    const orderBy = ref("");
    const sortColumns = [
      { value: "title", label: "Title" },
      { value: "description", label: "Description" },
      { value: "created_at", label: "Created" },
      { value: "completed_at", label: "Completed" },
      { value: "due_at", label: "Due" },
      { value: "priority", label: "Priority" },
    ];
    function sortTasks(order) {
      orderBy.value = order;
      fetchTasks();
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
      onTaskFilesUpdate,
      filters,
      taskFilterModal,
      onFilterTasks,
      orderBy,
      sortColumns,
      sortTasks,
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
