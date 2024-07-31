import { jwtDecode } from "jwt-decode";
import { token, clearToken } from "@/stores/tokenStore";

// MIDDLEWARE
const authMiddleware = (to, from, next) => {
  const token = localStorage.getItem("token");
  // TOKEN
  if (
    token !== null &&
    token !== undefined &&
    token !== "" &&
    token !== "undefined"
  ) {
    const decoded = jwtDecode(token);
    const expiration = new Date(decoded.exp * 1000);

    if (expiration > new Date()) {
      // TOKEN VALID
      next();
      return;
    } else {
      // TOKEN INVALID
      clearToken();
      next({ name: "login" });
      return;
    }
    // NO TOKEN
  } else {
    if (
      to.name !== "login" &&
      to.name !== "inscription" &&
      to.name !== "home"
    ) {
      clearToken();
      next({ name: "home" });
      return;
    } else {
      next();
      return;
    }
  }
};

export default authMiddleware;
