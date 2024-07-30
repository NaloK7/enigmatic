import { ref } from "vue";

const token = ref(localStorage.getItem("token"));

function setToken(newToken) {
  token.value = newToken;
  localStorage.setItem("token", newToken);
}

function clearToken() {
  token.value = null;
  localStorage.removeItem("token");
}

export { token, setToken, clearToken };
