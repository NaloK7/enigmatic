import axios from "axios";
const baseURL = import.meta.env.VITE_API_BASE_URL;
const apiEnigm = axios.create({
  baseURL,
});

apiEnigm.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token !== null && token !== undefined && token !== "") {
    config.headers["Authorization"] = `Bearer ${token.value}`;
  }
  return config;
});
export default apiEnigm;
