<template>
  <header>
    <div
      class="flex justify-between items-center dark-glass py-6 px-10 border-b border-primaryGreen relative z-10 [&>*]:w-1/5"
      id="top-header">
      <div class="w-1/5">
        <a class="logo-img" href="/">
          <img src="/logo-img.svg" alt="logo" />
        </a>
      </div>
      <div class="w-1/5 h-px bg-primaryGreen"></div>
      <a
        href="/"
        class="text-center text-gray-200 font-audiowide font-bold text-2xl px-4 min-w-80"
        >Enigmatic Journey
      </a>
      <div class="w-1/5 h-px bg-primaryGreen"></div>

      <div class="w-1/5 flex justify-end">
        <img src="./img/account-icon.svg" alt="menu icon" id="icon-account" />
      </div>
    </div>

    <nav
      class="dark-glass border-b border-primaryGreen  px-2 mx-auto w-1/2 flex items-center justify-evenly mb-5 rounded-b-xl relative backdrop-blur-md">
      <navBtn href="/riddle" text="Livre I"></navBtn>
      <navBtn href="/riddle" text="Livre II"></navBtn>
      <navBtn href="/riddle" text="Livre III"></navBtn>
      <navBtn href="/riddle" text="Livre IV"></navBtn>
    </nav>
  </header>
</template>
<script  setup>
import navBtn from "@/components/navBtn.vue";
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
