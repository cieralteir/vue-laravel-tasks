import { ref } from "vue";
import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", () => {
  const user = ref({});
  function setUser(value) {
    user.value = value;
  }

  const token = ref(localStorage.getItem("__token"));
  function setToken(value) {
    console.log(value);
    token.value = value;
    if (value) {
      localStorage.setItem("__token", value);
    } else {
      localStorage.removeItem("__token");
    }
  }

  return {
    user,
    setUser,
    token,
    setToken,
  };
});
