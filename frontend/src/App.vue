<template>
  <headerp></headerp>
  <!-- <banner v-if="!isTokenAvailable"></banner> -->
  <RouterView :key="$route.fullPath" />
</template>

<script setup>
import { RouterLink, RouterView } from "vue-router";
import headerp from "@/components/header.vue";
</script>
