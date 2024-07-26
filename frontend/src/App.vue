<template>
  <headerp></headerp>
  <!-- <banner v-if="!token"></banner> -->
  <banner></banner>
  <RouterView :key="$route.fullPath" />
</template>

<script setup>
import { RouterLink, RouterView } from "vue-router";
import headerp from "@/components/header.vue";
import banner from "@/components/loginBanner.vue";
import { ref, onUnmounted, onUpdated } from "vue";

const token = ref(null);
onUnmounted(async () => {
  token.value = await localStorage.getItem("token");
});
onUpdated(async () => {
  token.value = await localStorage.getItem("token");
});
</script>
