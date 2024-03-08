import { useEnvironment } from "@/composables/useEnvironment";

export function useApiRoutes() {
  const { API } = useEnvironment();

  const tracksApi = API + "tracks/moita";
  const landmarksApi = API + "landmarks/moita";

  const getTracksApi = (id: number) => `${tracksApi}/${id}`;
  const getLandmarksApi = (id: number) => `${landmarksApi}/${id}`;

  return {
    tracksApi,
    landmarksApi,
    getTracksApi,
    getLandmarksApi,
  };
}

export default useApiRoutes;
