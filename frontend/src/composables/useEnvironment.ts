export function useEnvironment() {
  const API = import.meta.env.VITE_API;
  const BASE = import.meta.env.VITE_BASE;

  return { API, BASE };
}

export default useEnvironment;
