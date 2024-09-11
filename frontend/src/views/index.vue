<template>
  <div class="dark-glass glass-border w-full py-2">
    <section
      class="px-6 w-11/12 sm:w-3/5 md:px-0 md:w-2/5 mx-auto space-y-2 text-gray-200">
      <h1 class="text-center underline font-audiowide mb-4 text-lg md:text-2xl">
        Bienvenue sur Enigmatic&nbspJourney
      </h1>
      <div>
        <span><li class="font-bold">Comment cela fonctionne ?</li></span>
        <span>- les énigmes sont rangées dans quatre livres.</span>
        <span>- la difficulté augmente au fur et à mesure.</span>
      </div>

      <div>
        <span
          ><li class="font-bold">Cette histoire de livre c’est quoi ?</li></span
        >
        <span>- vous avez quatre livres à parcourir comme bon vous semble.</span>
        <span>- ils n’ont aucune énigmes en commun.</span>
      </div>

      <div>
        <span
          ><li class="font-bold">Comment se passe la progression ?</li></span
        >
        <span
          >- vous <b>devez résoudre</b> une énigme pour passer à la
          suivante.</span
        >
        <span>OU</span>
        <span
          >- vous <b>pouvez passer</b> une énigme au prix d’une chose précieuse
          que l’on ne prend plus le temps d’apprécier à sa juste valeur...</span
        >
      </div>
      <span class="font-bold text-lg text-center">C'est parti ?</span>
      <div >
        <navBtn
          section="1"
          text="I"
          class="mx-auto rounded-md before:content-['Livre\00a0']"></navBtn>
      </div>
    </section>
  </div>
</template>

<script setup>
import navBtn from "@/components/navBtn.vue";
</script>
<style scoped>
span {
  display: block;
  font-weight: 500;
}
</style>
