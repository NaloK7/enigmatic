import { createRouter, createWebHistory } from "vue-router";
import authMiddleware from "./authMiddleware";
import Home from "@/views/index.vue";
import Book from "@/views/book/[book_id]/view/[id].vue";
import Riddle from "@/views/book/[book_id]/riddle/view/[id].vue";
import Login from "@/views/user/login.vue";
import Inscription from "@/views/user/inscription.vue";
import Books from "@/views/book/[id].vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: Home,
    },
    {
      path: "/book/:id",
      name: "books",
      component: Books,
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

router.beforeEach(authMiddleware);

export default router;
