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
            @click="toogleDropDown()"
            width="30"
            height="30"
            type="mdi"
            :path="accountIco"></svg-icon>
        </RouterLink>
        <ul
          v-if="visible"
          class="absolute top-[102px] right-0 w-32 dark-glass rounded-b-md border border-primaryGreen text-center text-gray-200 font-audiowide">
          <li>
            <button class="h-8 w-full hover:text-primaryPink">Profile</button>
          </li>
          <li class="border-t border-gray-500">
            <button class="h-8 w-full hover:text-primaryPink" @click="logout()">
              Déconnexion
            </button>
          </li>
        </ul>
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
import { ref, defineEmits } from "vue";
import { mdiAccount, mdiBookshelf } from "@mdi/js";
const accountIco = ref(mdiAccount);
const booksIco = ref(mdiBookshelf);
const visible = ref(false);
const emit = defineEmits();
function toogleDropDown() {
  visible.value = !visible.value;
}

function logout() {
  emit("removeToken");
}
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
