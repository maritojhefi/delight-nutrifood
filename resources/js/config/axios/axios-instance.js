// config/axios/axios-instance.js
import { setupCache } from 'axios-cache-interceptor';
import axios from 'axios';

const cachedAxios = setupCache(axios.create({
  baseURL: process.env.MIX_API_URL, // optional
}), {
  debug: true // optional: enables response.cached
});

export default cachedAxios;
