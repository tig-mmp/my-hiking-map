import axiosInstance from "axios";

const axios = axiosInstance.create();
const getAxiosAuth = () => axios.defaults.headers.common.Authorization;
const setAxiosAuth = (token: string) => axios.defaults.headers.common.Authorization = `Bearer ${token}`;

export { axios, getAxiosAuth, setAxiosAuth };
