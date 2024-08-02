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
  async postUser(url, criteria) {
    const response = await apiEnigm.post(url, { ...criteria });
    return response;
  },

  async getUser(url, criteria) {
    const response = await apiEnigm.post(url, { ...criteria });
    return response;
  },

  async getAll(url) {
    const response = await apiEnigm.post(url);
    return response;
  },

  async getLast(url, bookId) {
    const response = await apiEnigm.post(url, {
      body: { bookId },
    });
    return response.data;
  },
};

export default api;
