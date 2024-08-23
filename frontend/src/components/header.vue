<template>
  <header>
    <div
      class="flex justify-between items-center dark-glass py-4 md:py-6 px-10 border-b border-primaryGreen relative z-10 [&>*]:w-1/5"
      id="top-header">
      <div class="w-1/5">
        <RouterLink to="/">
          <img src="/logo-img.svg" alt="logo" class="h-10 md:h-14" />
        </RouterLink>
      </div>

      <div class="w-1/5 h-px bg-primaryGreen hidden md:block"></div>
      <RouterLink
        to="/"
        class="text-center text-gray-200 font-audiowide font-bold text-2xl px-4 min-w-80 hidden sm:block">
        Enigmatic Journey
      </RouterLink>

      <div class="w-1/5 h-px bg-primaryGreen hidden md:block"></div>

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
        <button
          v-if="tokenAvailable"
          @click="toogleDropDown()"
          @blur="() => (visible = false)">
          <svg-icon
            width="30"
            height="30"
            type="mdi"
            :path="accountIco"
            style="color: white">
          </svg-icon>
        </button>

        <ul
          v-if="visible"
          class="absolute top-[72px] md:top-[104px] right-3 w-32 dark-glass rounded-b-md border border-primaryGreen text-center text-gray-200 font-audiowide">
          <li>
            <button class="h-8 w-full hover:text-primaryPink">Profile</button>
          </li>
          <li class="border-t border-gray-500">
            <button
              class="h-8 w-full hover:text-primaryPink"
              @mousedown="removeToken()">
              Déconnexion
            </button>
          </li>
        </ul>
      </div>
    </div>
    <div class="nav-toggle static md:relative">
      <nav
        class="fixed bottom-0 z-50 dark-glass md:border-b md:border-primaryGreen mx-auto w-full flex items-center justify-evenly md:static md:w-2/3 md:px-2 md:rounded-b-xl lg:w-1/2">
        <navBtn section="1" text="I"></navBtn>
        <navBtn section="2" text="II"></navBtn>
        <navBtn section="3" text="III"></navBtn>
        <navBtn section="4" text="IV"></navBtn>
      </nav>
      <div class="h-4"></div>
    </div>
    <banner v-if="!tokenAvailable"></banner>
  </header>
</template>
<script setup>
import navBtn from "@/components/navBtn.vue";
import banner from "@/components/loginBanner.vue";
import SvgIcon from "@jamescoyle/vue-icon";
import { ref, watch } from "vue";
import { mdiAccount, mdiBookshelf } from "@mdi/js";
import { useRouter } from "vue-router";
import { token, clearToken } from "@/stores/tokenStore";

const router = useRouter();
const accountIco = ref(mdiAccount);
const booksIco = ref(mdiBookshelf);
const visible = ref(false);

function toogleDropDown() {
  visible.value = !visible.value;
}

const tokenAvailable = ref(false);

watch(
  token,
  () => {
    tokenAvailable.value =
      token.value !== null && token.value !== undefined && token.value !== "";
  },
  { immediate: true }
);

function removeToken() {
  clearToken();
  visible.value = false;
  router.push({ name: "home" });
}
</script>

<style scoped>
.svg-icon {
  fill: white;
}
#icon-account {
  width: 26px;
}
.nav-toggle {
  top: -50px;
  transition: 0.5s;
  transition-delay: 1000ms;
}
.nav-toggle:hover {
  top: 0px;
  transition: 0.5s;
}
</style>
