<template>
  <header>
    <div class="flex glass-header" id="top-header">
      <div>
        <a class="logo-img" href="#">
          <img src="/logo-img.svg" alt="logo" />
        </a>
      </div>
      <div class="line"></div>
      <a href="#" id="logo-title" class="font-audiowide font-bold text-2xl"
        >Enigmatic Journey
      </a>
      <div class="line"></div>
      <div>
        <img src="./img/account-icon.svg" alt="menu icon" id="icon-account" />
      </div>
    </div>
    <div class="glass-header" id="bottom-header">
      <nav>
        <a href="#" class="book-btn">Livre I</a>
        <a href="#" class="book-btn">Livre II</a>
        <a href="#" class="book-btn">Livre III</a>
        <a href="#" class="book-btn">Livre IV</a>
      </nav>
    </div>
  </header>
</template>

<style scoped>
.line {
  height: 1px;
  width: 100%;
  background-color: #17ae9f;
}

#icon-account {
  width: 26px;
}
.glass-header {
  background: linear-gradient(
    90deg,
    rgba(0, 0, 0, 0.1) 0%,
    rgba(0, 0, 0, 0.7) 40%,
    rgba(0, 0, 0, 0.7) 60%,
    rgba(0, 0, 0, 0.1) 100%
  );

  backdrop-filter: blur(10px);
}

div#top-header div {
  width: 20%;
}

#top-header {
  padding: 25px 40px;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0px 4px 15px var(--main-pink), 0px 2px 11px #535353;
  display: flex;
  position: relative;
  z-index: 2;
}

/* #top-header > div:nth-child(3) {
  display: flex;
  justify-content: end;
} */
#top-header > div:nth-child(5) {
  display: flex;
  justify-content: end;
}
#bottom-header {
  width: 60%;
  border-radius: 0px 0px 10px 10px;
  margin: 0 auto;
  justify-content: center;
  box-shadow: 0px 4px 15px var(--main-pink);
  position: relative;
  z-index: 1;
  backdrop-filter: blur(10px);
  top: -46px;
  transition: 0.5s;
  transition-delay: 1200ms;
}
#bottom-header:hover {
  transition: 0.5s;
  top: 0px;
}

nav {
  display: flex;
  align-items: center;
  justify-content: space-evenly;
  margin-bottom: 20px;
}

nav a {
  margin: 20px;
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
</style>
