import { createRouter, createWebHistory } from "vue-router";
import authMiddleware from "./authMiddleware";
import Home from "@/views/index.vue";
import Riddle from "@/views/book/[book_id]/riddle/view/[id].vue";
import Login from "@/views/user/login.vue";
import Inscription from "@/views/user/inscription.vue";
import Books from "@/views/book/[id].vue";
import Cgu from "@/views/legal/cgu.vue";
import Forget from "@/views/user/forgetPassword.vue";
import Reset from "@/views/user/resetPassword.vue";
import LegalNotice from "@/views/legal/legalNotice.vue";

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
    {
      path: "/cgu",
      name: "cgu",
      component: Cgu,
    },
    {
      path: "/legalNotice",
      name: "legalNotice",
      component: LegalNotice,
    },
    {
      path: "/forget",
      name: "forget",
      component: Forget,
    },
    {
      path: "/reset",
      name: "reset",
      component: Reset,
    },
  ],
});

router.beforeEach(authMiddleware);

export default router;
