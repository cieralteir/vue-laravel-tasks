import axios from "axios";
import { useAuthStore } from "../stores/useAuthStore";
import { useToaster } from "../stores/useToaster";

const api = axios.create({
  baseURL: `${import.meta.env.VITE_API_URL}`,
  headers: { "X-Requested-With": "XMLHttpRequest" },
  responseType: "json",
});

api.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore();
    const token = authStore.token;
    if (token) config.headers["Authorization"] = `Bearer ${token}`;
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

api.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    const taoster = useToaster();
    if (error.response.status >= 400 && error.response.status <= 500) {
      if (error.response?.data?.message) {
        taoster.toast(error.response?.data?.message, "error");
      }
    }
    // switch (error.response.status) {
    //   case 404:
    //     alert(error.response?.data?.message);
    //     break;
    // }
    return Promise.reject(error);
  }
);

export default api;
