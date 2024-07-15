<script setup>
import { RouterLink, RouterView } from "vue-router";
import home from "@/views/home.vue";
</script>

<template>
  <div id="bkg">
    <home></home>
  </div>
</template>

<style scoped>
#bkg {
  min-height: 100vh;
  background-image: url("bkg_dark.svg");
  background-repeat: no-repeat;
  background-position: center top;
}
</style>
