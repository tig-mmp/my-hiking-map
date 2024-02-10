export function useEnvironment() {
  const API = import.meta.env.VITE_API;

  return {
    API,
  };
}

export default useEnvironment;
