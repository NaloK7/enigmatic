import { jwtDecode } from "jwt-decode";

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
    console.log("token");
    const decoded = jwtDecode(token);
    const expiration = new Date(decoded.exp * 1000);

    if (expiration > new Date()) {
      // TOKEN VALID
      console.log("token valid");
      next();
      return;
    } else {
      // TOKEN INVALID
      console.log("token expired");
      localStorage.removeItem("token");
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
      console.log("no token");
      localStorage.removeItem("token");
      next({ name: "home" });
      return;
    } else {
      console.log("no token: to login/inscription/home");
      next();
      return;
    }
  }
};

export default authMiddleware;
