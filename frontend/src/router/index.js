import { createRouter, createWebHistory } from "vue-router";
import { jwtDecode } from "jwt-decode";
import Home from "@/views/index.vue";
import Book from "@/views/book/[book_id]/view/[id].vue";
import Riddle from "@/views/book/[book_id]/riddle/view/[id].vue";
import Login from "@/views/user/login.vue";
import Inscription from "@/views/user/inscription.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: Home,
    },
    {
      path: "/book/:book_id/view/:id",
      name: "book",
      component: Book,
    },
    {
      path: "/book/:book_id/riddle/view/:id",
      name: "riddle",
      component: Riddle,
    },
    {
      path: "/login",
      name: "login",
      component: Login,
    },
    {
      path: "/inscription",
      name: "inscription",
      component: Inscription,
    },
  ],
});

// MIDDLEWARE
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");
  console.log("middleware");
  if (token.value !== undefined) {
    const decoded = jwtDecode(token);
    const expiration = new Date(decoded.exp * 1000);

    if (expiration > new Date()) {
      next();
      return;
    }
  }
  if (to.name !== "login" && to.name !== "inscription") {
    next({ name: "login" });
  } else {
    next();
  }
});

export default router;
