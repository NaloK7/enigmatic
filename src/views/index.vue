<template>
  <banner></banner>
  <div class="dark-glass w-full py-6">
    <section class="w-2/5 mx-auto space-y-4 text-gray-200">
      <h1 class="text-center">Bienvenue sur Enigmatic Journey</h1>
      <div>
        <span><li class="font-bold">Comment ça fonctionne ?</li></span>
        <span>- les énigmes sont rangés dans quatre livre.</span>
        <span>- la difficulté augmente au fur et a mesure.</span>
      </div>

      <div>
        <span
          ><li class="font-bold">Cette histoire de livre c’est quoi ?</li></span
        >
        <span>- vous avez quatre livre à parcourir comme bon vous semble.</span>
        <span>- ils n’ont aucune énigmes communne.</span>
      </div>

      <div>
        <span
          ><li class="font-bold">Comment ce passe la progression ?</li></span
        >
        <span>- vous devez resoudre une enigme pour passer à la suivante.</span>
        <span>OU</span>
        <span
          >- vous pouvez passer une énigme au prix d’une chose precieuse que
          l’on ne prend plus le temps d’apprecier à sa juste valeur.</span
        >
      </div>
      <span class="font-bold text-lg text-center">C'est partit ?</span>
      <div class="flex justify-center">
        <navBtn class="" to="/book/1/riddle/view/1" text="Livre I"></navBtn>
      </div>
    </section>
  </div>
</template>
<script setup>
import banner from "@/components/loginBanner.vue";
import navBtn from "@/components/navBtn.vue";
</script>
<style scoped>
span {
  display: block;
}
</style>
