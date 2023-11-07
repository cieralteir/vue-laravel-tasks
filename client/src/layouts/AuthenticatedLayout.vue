<template>
  <div>
    <nav class="flex justify-between items-center p-2 px-5 bg-neutral-800">
      <div>
        <h1 class="text-2xl">TO<b>DO</b></h1>
      </div>
      <div class="flex gap-5">
        <button>{{ authStore.user?.email }}</button>
        <button
          class="p-2 px-4 rounded-md bg-neutral-900 hover:bg-neutral-700"
          @click="logout"
        >
          LOGOUT
        </button>
      </div>
    </nav>
    <div>
      <slot />
    </div>
  </div>
</template>

<script>
import { onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/useAuthStore";
import AuthService from "../services/auth.service";

export default {
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();

    async function fetchAuthUser() {
      try {
        const response = await AuthService.user();
        authStore.setUser(response.data);
      } catch (err) {
        console.error(err);
      }
    }

    async function logout() {
      try {
        await AuthService.logout();
        authStore.setUser(null);
        authStore.setToken(null);
        router.replace({ name: "login" });
      } catch (err) {
        console.error(err);
      }
    }

    onMounted(() => {
      fetchAuthUser();
    });

    return {
      authStore,
      logout,
    };
  },
};
</script>
