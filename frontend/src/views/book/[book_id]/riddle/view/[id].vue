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
      <div class="mt-auto h-7">
        <input
          type="text"
          v-model="answer"
          name="answer"
          id="answer"
          placeholder="réponse"
          class="rounded-l-lg w-96 h-full pl-2 border border-gray-500 bg-gray-200 text-gray-800 focus:outline-none" />
        <button
          class="rounded-r-lg w-16 h-full font-semibold border bg-gray-200 border-gray-500 text-gray-800 hover:bg-primaryGreen hover:text-white"
          @click="checkAnswer()">
          Valider
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
    <overlay
      :text="explanation"
      :display="display"
      @closeOverlay="closeOverlay()"
      @next="refresh()"></overlay>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import apiEnigm from "@/router/interceptor";
import navBtn from "@/components/navBtn.vue";
import overlay from "@/components/ExplanationOverlay.vue";

const route = useRoute();
const router = useRouter();
const bookId = route.params.book_id;
const riddleId = route.params.id;

const blocked = ref(false);
const expirationDate = ref();
const dayDifference = ref();

const position = ref(0);
const title = ref("");
const wording = ref("");
const explanation = ref("");
const display = ref(false);

const answer = ref("");

async function isBlocked() {
  const xhr = await apiEnigm.post("?action=blocked", {
    bookId: bookId,
  });

  const response = await xhr;
  if (response.status == 200) {
    await getLastRiddle();
    router.push(`/book/${bookId}/riddle/view/${position.value}`);
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
    explanation.value = response.data["explanation"];
  } else {
    console.log(response.status);
  }
}

async function checkAnswer() {
  if (answer.value != "") {
    const xhr = await apiEnigm.post("?action=checkAnswer", {
      riddleId: riddleId,
      answer: answer.value,
    });
    const response = await xhr;
    if (response.status == 200) {
      // popup explanation + push next riddle
      showOverlay();
    } else if (response.status == 204) {
      // feedback bad anwser
      console.log("bad answer");
    } else {
      // feedback an error ocurred (no response found)
    }
  }
}

function showOverlay() {
  display.value = true;
}

function closeOverlay() {
  display.value = false;
}

async function refresh() {
  closeOverlay();
  const xhr = await apiEnigm.post("?action=solve", {
    riddleId: riddleId,
  });
  const response = await xhr;
  console.log(response.status);
  // query post id riddle to valid user riddle
  await isBlocked();
}
onMounted(async () => {
  if (riddleId == "all") {
    await isBlocked();
  } else {
    await getLastRiddle();
  }
});
</script>
