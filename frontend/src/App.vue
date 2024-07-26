<template>
  <headerp></headerp>
  <banner v-if="!isTokenAvailable"></banner>
  <RouterView :key="$route.fullPath" />
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { RouterLink, RouterView } from "vue-router";
import headerp from "@/components/header.vue";
import banner from "@/components/loginBanner.vue";

const token = ref(null);

onMounted(() => {
  token.value = localStorage.getItem("token");
});

const isTokenAvailable = computed(() => {
  return (
    token.value !== null && token.value !== undefined && token.value !== ""
  );
});
</script>
