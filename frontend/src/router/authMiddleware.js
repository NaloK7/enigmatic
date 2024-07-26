import { jwtDecode } from "jwt-decode";

// MIDDLEWARE
const authMiddleware = (to, from, next) => {
  const token = localStorage.getItem("token");
  if (token != null && token != undefined && token != "") {
    const decoded = jwtDecode(token);
    const expiration = new Date(decoded.exp * 1000);

    if (expiration > new Date()) {
      next();
      return;
    }
  }
  if (to.name !== "login" && to.name !== "inscription" && to.name !== "home") {
    localStorage.removeItem("token");
    next({ name: "home" });
  } else {
    next();
  }
};

export default authMiddleware;
