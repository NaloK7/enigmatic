<template>
  <header>
    <div
      class="flex justify-between items-center dark-glass py-6 px-10 border-b border-primaryGreen relative z-10 [&>*]:w-1/5"
      id="top-header">
      <div class="w-1/5">
        <RouterLink to="/" class="logo-img">
          <img src="/logo-img.svg" alt="logo" />
        </RouterLink>
      </div>
      <div class="w-1/5 h-px bg-primaryGreen"></div>
      <RouterLink
        to="/"
        class="text-center text-gray-200 font-audiowide font-bold text-2xl px-4 min-w-80">
        Enigmatic Journey
      </RouterLink>

      <div class="w-1/5 h-px bg-primaryGreen"></div>

      <div class="w-1/5 flex justify-end space-x-2">
        <!-- BOOKS -->
        <RouterLink to="/book/all">
          <svg-icon
            width="30"
            height="30"
            type="mdi"
            :path="booksIco"></svg-icon>
        </RouterLink>
        <!-- ACCOUNT -->
        <RouterLink to="/">
          <svg-icon
            width="30"
            height="30"
            type="mdi"
            :path="accountIco"></svg-icon>
        </RouterLink>
      </div>
    </div>

    <nav
      class="dark-glass border-b border-primaryGreen px-2 mx-auto w-1/2 flex items-center justify-evenly mb-5 rounded-b-xl relative backdrop-blur-md">
      <navBtn to="/book/1/view/1" text="Livre I"></navBtn>
      <navBtn to="/book/2/view/2" text="Livre II"></navBtn>
      <navBtn to="/book/3/view/3" text="Livre III"></navBtn>
      <navBtn to="/book/4/view/4" text="Livre IV"></navBtn>
    </nav>
  </header>
</template>
<script setup>
import navBtn from "@/components/navBtn.vue";
import SvgIcon from "@jamescoyle/vue-icon";
import { ref } from "vue";
import { mdiAccount, mdiBookshelf } from "@mdi/js";
const accountIco = ref(mdiAccount);
const booksIco = ref(mdiBookshelf);
</script>

<style scoped>
#icon-account {
  width: 26px;
}
nav {
  top: -50px;
  transition: 0.5s;
  transition-delay: 1000ms;
}
nav:hover {
  transition: 0.5s;
  top: 0px;
}
</style>
