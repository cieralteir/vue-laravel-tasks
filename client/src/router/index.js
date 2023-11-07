import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/useAuthStore";

const routes = [
  {
    path: "/",
    name: "tasks",
    component: () => import("@/views/tasks/index.vue"),
  },
  {
    path: "/login",
    name: "login",
    component: () => import("@/views/login/index.vue"),
    meta: {
      public: true,
    },
  },
  {
    path: "/register",
    name: "register",
    component: () => import("@/views/register/index.vue"),
    meta: {
      public: true,
    },
  },
  {
    path: "/:pathMatch(.*)*",
    name: "404",
    component: () => import("@/views/404/index.vue"),
    meta: {
      public: true
    }
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach(async (to, from) => {
  const authStore = useAuthStore();
  const isAuthenticated = !!authStore.token;
  // Redirect guest to login page
  if (!isAuthenticated && !to.meta?.public) {
    return { name: "login" };
  }
  // Redirect user to home page
  if (isAuthenticated && to.meta?.public) {
    return { name: "tasks" };
  }
});

export default router;
