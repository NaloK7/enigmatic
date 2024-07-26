import { jwtDecode } from "jwt-decode";

// MIDDLEWARE
const authMiddleware = (to, from, next) => {
  const token = localStorage.getItem("token");
  if (
    token !== null &&
    token !== undefined &&
    token !== "" &&
    token !== "undefined"
  ) {
    console.log("1");
    const decoded = jwtDecode(token);
    const expiration = new Date(decoded.exp * 1000);

    if (expiration > new Date()) {
      console.log("2");
      next();
    } else {
      console.log("3");
      // localStorage.removeItem("token");
      next({ name: "login" });
    }
  }
  if (to.name !== "login" && to.name !== "inscription" && to.name !== "home") {
    console.log("4");
    // localStorage.removeItem("token");
    next({ name: "home" });
  } else {
    next();
  }
};

export default authMiddleware;
