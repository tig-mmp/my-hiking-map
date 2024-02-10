import { useEnvironment } from "@/composables/useEnvironment";

export function useApiRoutes() {
  const { API } = useEnvironment();

  const loginApi = API + "login";

  return {
    loginApi,
  };
}

export default useApiRoutes;
