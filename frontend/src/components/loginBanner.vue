<template>
  <div
    class="dark-glass glass-border p-4 mb-5 flex justify-center items-center">
    <cta text="connexion" link="/login"></cta>
    <cta text="inscription" link="/inscription"></cta>
  </div>
</template>
<script setup>
import cta from "@/components/ctaBtn.vue";
</script>

<style scoped></style>
