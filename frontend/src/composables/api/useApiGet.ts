import { ref, type Ref } from "vue";
import { axios } from "@/composables/axiosInstance";
import { type ToastServiceMethods } from "primevue/toastservice";
import type { AxiosResponse } from "axios";

export default function useApiGet<T>(
  toast: ToastServiceMethods | null,
  defaultResult: T
) {
  const data = ref(defaultResult) as Ref<T>;
  const isLoading = ref(false);
  let attempts = 0;
  type ResponseData = {
    data: T;
    totalRecords?: number;
  };

  async function load(
    url: string,
    params?: { [key: string]: any }
  ): Promise<AxiosResponse<ResponseData, any>> {
    isLoading.value = true;
    const config = {
      headers: { "Content-Type": "text/plain" },
      params,
    };
    return await axios
      .get<ResponseData>(url, config)
      .then(response => {
        isLoading.value = false;
        data.value = response.data.data;
        return response;
      })
      .catch(async error => {
        if (attempts == 0) {
          attempts = 1;
          return load(url, params)
            .then(response => response)
            .catch(async error => {
              throw error;
            });
        }
        isLoading.value = false;
        if (
          error.response?.data instanceof Blob &&
          error.response?.data?.type === "application/json"
        ) {
          error.response.data = JSON.parse(await error.response.data.text());
        }
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

  return { data, isLoading, load };
}
