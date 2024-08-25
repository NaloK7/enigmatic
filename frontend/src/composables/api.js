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
  async postUser(email, password) {
    const response = await apiEnigm.post(`inscription`, { email, password });
    return response;
  },

  async getUser(email, password) {
    const response = await apiEnigm.post(`login`, { email, password });
    return response;
  },

  async getAll() {
    const response = await apiEnigm.post("books");
    return response;
  },

  async getOne(bookId, riddlePos) {
    const response = await apiEnigm.post("riddle", { bookId, riddlePos });
    return response;
  },

  async getLast(bookId) {
    const response = await apiEnigm.post("last", { bookId });
    return response;
  },
  async isLocked(bookId) {
    const response = await apiEnigm.post("isLocked", { bookId });
    return response;
  },
  async lockBook(bookId) {
    const response = await apiEnigm.post("lockBook", { bookId });
    return response;
  },
  async checkAnswer(riddleId, answer) {
    const response = await apiEnigm.post("checkAnswer", { riddleId, answer });
    return response;
  },
  async getAnswer(riddleId, answer) {
    const response = await apiEnigm.post("getAnswer", { riddleId });
    return response;
  },
  async getExplanation(riddleId) {
    const response = await apiEnigm.post("explanation", { riddleId });
    return response;
  },
  async postSolved(riddleId) {
    const response = await apiEnigm.post("solve", { riddleId });
    return response;
  },
  async isFinished(bookId) {
    const response = await apiEnigm.post("finish", { bookId });
    return response;
  },
};

export default api;
