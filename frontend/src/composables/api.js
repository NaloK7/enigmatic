import axios from "axios";

const baseURL = import.meta.env.VITE_API_BASE_URL;

const apiEnigm = axios.create({
  baseURL,
});

apiEnigm.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers["Authorization"] = `Bearer ${token}`;
  }
  config.headers["Content-Type"] = "application/json";

  return config;
});

const api = {
  async getAll(url) {
    const response = await apiEnigm.get(url);
    return response;
  },

  async getOne(url, criteria) {
    const response = await apiEnigm.get(url, {params:{ ...criteria }});
    return response;
  },

  async postOne(url, criteria) {
    const response = await apiEnigm.post(url, { ...criteria });
    return response;
  },

  async updateOne(url, criteria) {
    const response = await apiEnigm.post(url, { ...criteria });
    return response;
  },
};

export default api;
