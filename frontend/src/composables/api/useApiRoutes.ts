import { useEnvironment } from "@/composables/useEnvironment";

export function useApiRoutes() {
  const { API } = useEnvironment();

  const uploadApi = API + "upload";
  const uploadMultipleApi = API + "upload-multiple";
  const fileApi = API + "file";

  const loginApi = API + "login";
  const usersApi = API + "users";
  const tracksApi = API + "tracks";
  const districtsApi = API + "districts";
  const landmarksApi = API + "landmarks";
  const landmarksMultipleApi = API + "landmarks-multiple";

  const getUsersApi = (id: number) => `${usersApi}/${id}`;
  const getTracksApi = (id: number) => `${tracksApi}/${id}`;
  const getLandmarksApi = (id: number) => `${landmarksApi}/${id}`;

  return {
    uploadApi,
    uploadMultipleApi,
    fileApi,
    loginApi,
    usersApi,
    tracksApi,
    districtsApi,
    landmarksApi,
    landmarksMultipleApi,
    getUsersApi,
    getTracksApi,
    getLandmarksApi,
  };
}

export default useApiRoutes;
