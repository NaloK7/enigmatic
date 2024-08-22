<template>
  <div class="min-h-screen flex flex-col">
    <headerComp></headerComp>
    <RouterView :key="$route.fullPath" />
    <div class="flex-1"></div>
    <footerComp class="mt-4 bottom-0"></footerComp>
  </div>
</template>

<script setup>
import { RouterView } from "vue-router";
import headerComp from "@/components/header.vue";
import footerComp from "@/components/footer.vue";
</script>
