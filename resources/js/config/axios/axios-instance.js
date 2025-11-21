// config/axios/axios-instance.js
import { setupCache } from "axios-cache-interceptor";
import axios from "axios";
import loaderManager from "../loaders/loader-manager.js";

const cachedAxios = setupCache(
    axios.create({
        baseURL: process.env.MIX_API_URL,
    }),
    {
        debug: true,
    },
);

const loaderAxios = axios.create({
    baseURL: process.env.MIX_API_URL,
});

loaderAxios.interceptors.request.use(
    (config) => {
        loaderManager.setIsLoading(true);
        return config;
    },
    (error) => {
        loaderManager.setIsLoading(false);
        return Promise.reject(error);
    },
);

loaderAxios.interceptors.response.use(
    (response) => {
        loaderManager.setIsLoading(false);
        return response;
    },
    (error) => {
        loaderManager.setIsLoading(false);
        return Promise.reject(error);
    },
);

export { cachedAxios, loaderAxios };
export default cachedAxios;
