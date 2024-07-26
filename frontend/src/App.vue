<template>
  <headerp></headerp>
  <banner v-if="!token"></banner>
  <RouterView :key="$route.fullPath" />
</template>

<script setup>
import { RouterLink, RouterView } from "vue-router";
import headerp from "@/components/header.vue";
import banner from "@/components/loginBanner.vue";
import { ref, onUpdated } from "vue";

const token = ref(null);
onUpdated(async () => {
  token.value = await localStorage.getItem("token");
});
onUpdated(async () => {
  token.value = await localStorage.getItem("token");
});
</script>
