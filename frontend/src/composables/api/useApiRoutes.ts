import { useEnvironment } from "@/composables/useEnvironment";

export function useApiRoutes() {
  const { API } = useEnvironment();

  const uploadApi = API + "upload";
  const loginApi = API + "login";
  const usersApi = API + "users";
  const tracksApi = API + "tracks";
  const districtsApi = API + "districts";

  const getUsersApi = (id: number) => `${usersApi}/${id}`;
  const getTracksApi = (id: number) => `${tracksApi}/${id}`;

  return {
    uploadApi,
    loginApi,
    usersApi,
    tracksApi,
    districtsApi,
    getUsersApi,
    getTracksApi,
  };
}

export default useApiRoutes;
