<template>
  <section class="dark-glass border-y border-primaryGreen mb-10">
    <!-- RIDDLE -->
    <div
      v-if="!blocked"
      class="flex flex-col justify-start items-center w-1/2 px-10 py-6 space-y-4 mx-auto">
      <h2 class="pb-4 font-audiowide text-xl text-primaryPink text-shadow-pink">
        {{ riddle.section_id }}-{{ riddle.position }}. {{ riddle.title }}
      </h2>
      <p class="riddle-txt text-gray-200" v-html="riddle.wording"></p>
      <!-- separator -->
      <div></div>
      <div
        class="mt-auto h-7 w-full grid"
        style="grid-template-columns: 1fr 2fr 1fr">
        <div
          :class="{
            'animate-shake border border-red-500 rounded-lg': badAnswer,
          }"
          class="col-start-2 flex">
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
        <button
          class="col-start-3 ml-auto rounded-lg w-16 h-full font-semibold border bg-gray-200 border-gray-500 text-gray-800 hover:bg-primaryGreen hover:text-white"
          @click="showOverlay('giveUp')">
          Passer
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
        <navBtn section="1" text="Livre I"></navBtn>
        <navBtn section="2" text="Livre II"></navBtn>
        <navBtn section="3" text="Livre III"></navBtn>
        <navBtn section="4" text="Livre IV"></navBtn>
      </div>
    </div>
    <overlay
      :text="explanation"
      :display="displayOverlay"
      @closeOverlay="closeOverlay()"
      @next="refresh()"></overlay>
    <giveUpOverlay
      :display="displayGiveUp"
      :answer="answer"
      :text="explanation"
      @closeOverlay="closeOverlay()"
      @giveUp="giveUp()"></giveUpOverlay>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "@/composables/api";
import navBtn from "@/components/navBtn.vue";
import overlay from "@/components/ExplanationOverlay.vue";
import giveUpOverlay from "@/components/giveUpOverlay.vue";

const route = useRoute();
const router = useRouter();
const bookId = route.params.book_id;
const riddlePos = route.params.id;

const blocked = ref(false);
const expirationDate = ref();
const dayDifference = ref();

const riddle = ref({});
const displayOverlay = ref(false);
const explanation = ref("");

const answer = ref("");
const badAnswer = ref(false);

const displayGiveUp = ref(false);

async function isBookLocked() {
  const response = await api.isLocked(bookId);
  if (response.status == 200) {
    getOneRiddle();
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

async function getOneRiddle() {
  const response = await api.getOne(bookId, riddlePos);

  if (response.status == 200) {
    riddle.value = response.data;
  } else {
    console.log(response.status);
  }
}

async function checkAnswer() {
  if (answer.value != "") {
    const response = await api.checkAnswer(riddle.value.riddleId, answer.value);

    if (response.status == 200) {
      // popup explanation + push next riddle
      const response = await api.getExplanation(riddle.value.riddleId);
      explanation.value = response.data.explanation;
      showOverlay("next");
    } else if (response.status == 204) {
      badAnswer.value = true;
    } else {
      // feedback an error ocurred (no response found)
    }
  } else {
    badAnswer.value = true;
  }
  setTimeout(() => {
    badAnswer.value = false;
  }, 700);
}

function showOverlay(item) {
  switch (item) {
    case "next":
      displayOverlay.value = true;
      break;
    case "giveUp":
      displayGiveUp.value = true;
      break;

    default:
      break;
  }
}

function closeOverlay() {
  displayOverlay.value = false;
  displayGiveUp.value = false;
  isBookLocked();
}

async function giveUp() {
  // give answer
  const xhrSolution = await api.getAnswer(riddle.value.riddleId);
  answer.value = xhrSolution.data.solution;
  const xhrExplanation = await api.getExplanation(riddle.value.riddleId);
  explanation.value = xhrExplanation.data.explanation;
  // post solve
  const xhrSolve = await api.postSolved(riddle.value.riddleId);
  // post lock book
  const xhrLockBook = await api.lockBook(bookId);
}
async function refresh() {
  closeOverlay();
  const response = await api.postSolved(riddle.value.riddleId);
  if (response.status == 200) {
    // push to 31 instead of 3 + 1
    router.push(`/book/${bookId}/riddle/view/${riddlePos + 1}`);
  } else {
    console.log(response.status);
  }
}
onMounted(() => {
  isBookLocked();
});
</script>
