import { useEnvironment } from "@/composables/useEnvironment";

export function useApiRoutes() {
  const { API } = useEnvironment();

  const loginApi = API + "login";
  const usersApi = API + "users";

  const getUsersApi = (id: number) => `${usersApi}/${id}`;

  return {
    loginApi,
    usersApi,
    getUsersApi,
  };
}

export default useApiRoutes;
