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
  const landmarkTypesApi = API + "landmark-types";

  const getUsersApi = (id: number) => `${usersApi}/${id}`;
  const getTracksApi = (id: number) => `${tracksApi}/${id}`;
  const getLandmarksApi = (id: number) => `${landmarksApi}/${id}`;
  const getLandmarkTypesApi = (id: number) => `${landmarkTypesApi}/${id}`;

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
    landmarkTypesApi,
    getUsersApi,
    getTracksApi,
    getLandmarksApi,
    getLandmarkTypesApi,
  };
}

export default useApiRoutes;
