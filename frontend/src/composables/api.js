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
  // OK
  async postUser(url, email, password) {
    const response = await apiEnigm.post(url, { email, password });
    return response;
  },
  // OK
  async getUser(url, email, password) {
    const response = await apiEnigm.post(url, { email, password });
    return response;
  },
  // OK
  async getAll(url) {
    const response = await apiEnigm.post(url);
    return response;
  },

  async getOne(url, bookId, riddlePos) {
    const response = await apiEnigm.post(url, { bookId, riddlePos });
    return response;
  },

  async getLast(url, bookId) {
    const response = await apiEnigm.post(url, { bookId });
    return response;
  },
  async isLocked(url, bookId) {
    const response = await apiEnigm.post(url, { bookId });
    return response;
  },
};

export default api;
