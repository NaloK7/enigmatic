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
      class="dark-glass border-b border-primaryGreen  mx-auto w-3/5 flex items-center justify-evenly mb-5 rounded-b-xl relative backdrop-blur-md">
      <a href="/riddle">Livre I</a>
      <a href="#">Livre II</a>
      <a href="#">Livre III</a>
      <a href="#">Livre IV</a>
    </nav>
  </header>
</template>

<style scoped>
nav a {
  margin: 22px;
  height: 28px;
  width: 92px;
  border: 2px solid #17ae9f;
  border-radius: 5px;
  background: #18544e;
  color: white;
  font-family: "Audiowide";
  text-align: center;
  line-height: 1.4em;
}
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
