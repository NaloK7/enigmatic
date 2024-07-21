import { createRouter, createWebHistory } from "vue-router";
import Home from "@/views/index.vue";
import Book from "@/views/book/[book_id]/view/[id].vue";
import Riddle from "@/views/book/[book_id]/riddle/view/[id].vue";

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

    // {
    //   path: "/about",
    //   name: "about",
    //   // route level code-splitting
    //   // this generates a separate chunk (About.[hash].js) for this route
    //   // which is lazy-loaded when the route is visited.
    //   component: () => import("../views/AboutView.vue"),
    // },
  ],
});

export default router;
