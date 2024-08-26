<template>
  <div class="dark-glass glass-border p-4">
    <span class="block text-center font-semibold text-gray-200"
      >Navigation</span
    >
    <span class="flex gap-4 justify-center flex-wrap mt-2 text-gray-400">
      <RouterLink to="/">Accueil</RouterLink>
      <RouterLink to="/login">connexion</RouterLink>
      <RouterLink to="/inscription">inscription</RouterLink>
      <RouterLink to="/book/:id">livres</RouterLink>
      <RouterLink to="/book/1/riddle/view/:id">livre I</RouterLink>
      <RouterLink to="/book/2/riddle/view/:id">livre II</RouterLink>
      <RouterLink to="/book/3/riddle/view/:id">livre III</RouterLink>
      <RouterLink to="/book/4/riddle/view/:id">livre IV</RouterLink>
    </span>
    <RouterLink to="/cgu" class="block text-center mt-4">CGU</RouterLink>
  </div>
</template>
<script setup>
import { RouterLink } from "vue-router";
</script>
<style scoped>
a {
  color: inherit;
}
a:hover {
  color: white;
}
</style>
