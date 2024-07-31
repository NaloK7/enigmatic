<template>
  <section class="dark-glass border-y border-primaryGreen my-10">
    <!-- RIDDLE -->
    <div
      v-if="!blocked"
      class="flex flex-col justify-start items-center w-1/2 px-10 py-6 space-y-4 mx-auto">
      <h2 class="pb-4 font-audiowide text-xl text-primaryPink text-shadow-pink">
        {{ bookId }}-{{ position }}. {{ title }}
      </h2>
      <p class="riddle-txt text-gray-200" v-html="wording"></p>
      <!-- separator -->
      <div></div>
      <div class="mt-auto">
        <input
          type="text"
          v-model="answer"
          name="answer"
          id="answer"
          placeholder="   réponse"
          class="rounded-l-lg w-96 border border-gray-500" />
        <button
          class="rounded-r-lg w-10 font-bold border bg-white border-gray-500 text-gray-800 hover:bg-primaryGreen hover:text-white">
          OK
        </button>
      </div>
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

const answer = ref("");

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
