export function useEnvironment() {
  const API = import.meta.env.VITE_API;
  const BASE = import.meta.env.VITE_BASE;
  const MAP_STYLE = import.meta.env.VITE_MAP_STYLE;

  return { API, BASE,MAP_STYLE };
}

export default useEnvironment;
