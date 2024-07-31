<template>
  <section class="dark-glass border-y border-primaryGreen my-10">
    <!-- RIDDLE -->
    <div
      v-if="!blocked"
      class="flex flex-col justify-start items-center w-3/5 px-10 py-0 mx-auto h-[367px]">
      <h2
        class="font-audiowide text-xl p-0 py-10 text-primaryPink text-shadow-pink">
        {{ position }}. {{ title }}
      </h2>

      <p class="riddle-txt text-gray-200" v-html="wording"></p>
    </div>

    <!-- BLOCKED FEEDBACK -->
    <div v-else class="mx-auto w-3/5 my-4 space-y-4">
      <span class="block text-primaryPink text-shadow-pink text-center text-xl"
        >Bloqué</span
      >
      <span class="block text-center text-lg text-gray-200"
        >Ce livre est bloqué pendant encore <b>{{ dayDifference }}</b> jours
        <br />Il sera debloqué le {{ expirationDate }}</span
      >
      <div
        class="px-2 mx-auto w-2/3 flex items-center justify-evenly rounded-b-xl">
        <navBtn to="/book/1/riddle/view/all" text="Livre I"></navBtn>
        <navBtn to="/book/2/riddle/view/all" text="Livre II"></navBtn>
        <navBtn to="/book/3/riddle/view/all" text="Livre III"></navBtn>
        <navBtn to="/book/4/riddle/view/all" text="Livre IV"></navBtn>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import apiEnigm from "@/router/interceptor";
import navBtn from "@/components/navBtn.vue";

const route = useRoute();
const bookId = route.params.book_id;

const blocked = ref(false);
const expirationDate = ref();
const dayDifference = ref();

const title = ref("");
const wording = ref("");
const position = ref(0);

async function isBlocked() {
  const xhr = await apiEnigm.post("?action=blocked", {
    bookId: bookId,
  });

  const response = await xhr;
  if (response.status == 200) {
    getLastRiddle();
  } else if (response.status == 202) {
    blocked.value = true;
    expirationDate.value = new Date(response.data["expiration"]);
    const currentDate = new Date();

    const timeDifference = expirationDate.value - currentDate;
    // Convert the time difference from milliseconds to days
    dayDifference.value = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
    expirationDate.value = expirationDate.value.toLocaleDateString("fr-FR");
  }
}

async function getLastRiddle() {
  const xhr = await apiEnigm.post("?action=last", {
    bookId: bookId,
  });
  const response = await xhr;
  if (response.status == 200) {
    console.log(response.data);
    //   response.data =  {
    //   "position": 3,
    //   "title": "Le nénuphar géant",
    //   "wording": "Un nénuphar se trouvant dans un lac double de taille chaque jour.</br>Au bout de 10 jours, il couvre la moitié du lac.</br></br>Combien de jours lui aura-t-il fallu en tout pour le recouvrir entièrement ?"
    // }
    title.value = response.data["title"];
    wording.value = response.data["wording"];
    position.value = response.data["position"];
  } else {
    console.log(response.status);
  }
}
onMounted(async () => {
  await isBlocked();
});
</script>
