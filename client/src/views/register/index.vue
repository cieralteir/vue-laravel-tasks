<template>
  <div class="w-96 p-9 rounded-md bg-neutral-800">
    <h1 class="text-2xl font-medium">REGISTER</h1>
    <form @submit.prevent="register">
      <div class="flex flex-col gap-4 my-9">
        <BaseInput
          v-model="form.email"
          label="Email"
          :attrs="{ type: 'email' }"
          :error="errorBag.errors.email ? errorBag.errors.email[0] : ''"
        />
        <BaseInput
          v-model="form.password"
          label="Password"
          :attrs="{ type: 'password' }"
          :error="errorBag.errors.password ? errorBag.errors.password[0] : ''"
        />
        <BaseInput
          v-model="form.password_confirmation"
          label="Confirm Password"
          :attrs="{ type: 'password' }"
        />
      </div>
      <button
        class="w-full p-4 mb-2 rounded-md bg-neutral-900 hover:bg-neutral-700"
        type="submit"
      >
        REGISTER
      </button>
      <button class="w-full p-4 rounded-md hover:bg-neutral-700" @click.prevent="login">
        LOGIN
      </button>
    </form>
  </div>
</template>

<script>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../../stores/useAuthStore";
import BaseInput from "../../components/Base/BaseInput.vue";
import AuthService from "../../services/auth.service";

export default {
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();
    const form = reactive({
      email: "",
      password: "",
      password_confirm: "",
    });
    const errorBag = reactive({ errors: {} });

    async function register() {
      try {
        const response = await AuthService.register(form);
        console.log(response);
        authStore.setUser(response.data.user);
        authStore.setToken(response.data.token);
        router.replace({ name: "tasks" });
      } catch (err) {
        console.error(err);
        errorBag.errors = err?.response?.data?.errors || {};
      }
    }

    function login() {
      router.replace({ name: "login" });
    }

    return {
      form,
      errorBag,
      register,
      login,
    };
  },
  components: {
    BaseInput,
  },
};
</script>
