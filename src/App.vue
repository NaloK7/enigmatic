<script setup>
import { RouterLink, RouterView } from "vue-router";
import headerp from "@/components/header.vue";
</script>

<template>
  <headerp></headerp>
  <RouterView :key="$route.fullPath" />
</template>
