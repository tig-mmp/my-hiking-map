import { ref } from "vue";
import { axios } from "@/composables/axiosInstance";
import { type ToastServiceMethods } from "primevue/toastservice";

export default function useApiPut(toast: ToastServiceMethods) {
  const isLoading = ref(false);

  async function put(url: string, params?: any) {
    isLoading.value = true;
    return await axios
      .put(url, params)
      .then(response => {
        isLoading.value = false;
        if (response?.data?.msg_code) {
          toast.add({
            severity: "success",
            summary: response.data.msg_code,
            life: 5000,
          });
        }
        return response;
      })
      .catch(error => {
        isLoading.value = false;
        if (error?.response?.data?.msg_code && toast) {
          toast.add({
            severity: "error",
            summary: error.response.data.msg_code,
            life: 10000,
          });
        }
        throw error;
      });
  }

  return { isLoading, put };
}
