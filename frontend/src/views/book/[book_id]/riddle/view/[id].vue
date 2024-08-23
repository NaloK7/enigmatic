<template>
  <section class="dark-glass border-y border-primaryGreen">
    <div v-if="finished">
      <span class="block p-4 text-center text-gray-200"
        >Bravo!!<br />Vous avez résolut toutes les énigmes de ce livre.</span
      >
    </div>
    <div v-else>
      <!-- RIDDLE -->
      <div
        v-if="!blocked"
        class="flex flex-col justify-start items-center w-11/12 sm:w-4/5 lg:w-1/2 lg:px-10 py-6 space-y-4 mx-auto">
        <h2
          class="pb-4 font-audiowide text-xl text-primaryPink text-shadow-pink">
          {{ riddle.section_id }}-{{ riddle.position }}. {{ riddle.title }}
        </h2>
        <p class="text-gray-200" v-html="riddle.wording"></p>

        <div
          class="mt-auto h-7 grid"
          style="grid-template-columns: 1fr 2fr 1fr">
          <div
            :class="{
              'animate-shake border border-red-500 rounded-lg': badAnswer,
            }"
            class="col-start-2 flex h-7">
            <input
              type="text"
              v-model="answer"
              name="answer"
              @keydown.enter="checkAnswer()"
              id="answer"
              placeholder="réponse"
              class="rounded-l-lg w-96 pl-2 border border-gray-500 bg-gray-200 text-gray-800 focus:outline-none" />
            <button
              class="rounded-r-lg w-16 font-semibold border bg-gray-200 border-gray-500 text-gray-800 hover:bg-primaryGreen hover:text-white"
              @click="checkAnswer()">
              Valider
            </button>
          </div>
          <button
            class="col-start-3 ml-4 rounded-lg w-16 h-full font-semibold border bg-gray-200 border-gray-500 text-gray-800 hover:bg-primaryGreen hover:text-white"
            @click="showOverlay('giveUp')">
            Passer
          </button>
        </div>
      </div>

      <!-- BLOCKED FEEDBACK -->
      <div v-else class="mx-auto w-4/5 md:w-3/5 my-4 space-y-4">
        <span
          class="block text-primaryPink text-shadow-pink text-center text-xl"
          >Bloqué</span
        >
        <span class="block text-center text-lg text-gray-200"
          >Ce livre est bloqué pendant: <b>{{ dayDifference }}</b> jours
          <br />Il sera debloqué le {{ expirationDate }}</span
        >
        <div
          class="px-2 mx-auto hidden md:flex items-center justify-evenly rounded-b-xl">
          <navBtn section="1" text="I"></navBtn>
          <navBtn section="2" text="II"></navBtn>
          <navBtn section="3" text="III"></navBtn>
          <navBtn section="4" text="IV"></navBtn>
        </div>
      </div>
    </div>
    <overlay
      :text="explanation"
      :display="displayOverlay"
      @closeOverlay="closeOverlay()"
      @next="goNext()"></overlay>
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

const finished = ref(false);

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
  const xhrFinish = await api.isFinished(bookId);
  if (xhrFinish.data) {
    finished.value = true;
  } else {
    const response = await api.isLocked(bookId);
    expirationDate.value = new Date(response.data);

    const currentDate = new Date();
    currentDate.setHours(0, 0, 0, 0);

    if (currentDate >= expirationDate.value) {
      getOneRiddle();
    } else {
      blocked.value = true;
      const timeDifference = expirationDate.value - currentDate;
      // Convert the time difference from milliseconds to days
      dayDifference.value = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
      expirationDate.value = expirationDate.value.toLocaleDateString("fr-FR");
    }
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
      solveRiddle();
      // popup explanation
      const response = await api.getExplanation(riddle.value.riddleId);
      explanation.value = response.data.explanation;
      showOverlay("next");
    } else {
      badAnswer.value = true;
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
  // get answer
  const xhrSolution = await api.getAnswer(riddle.value.riddleId);
  answer.value = xhrSolution.data.solution;
  const xhrExplanation = await api.getExplanation(riddle.value.riddleId);
  explanation.value = xhrExplanation.data.explanation;
  // post solve
  solveRiddle();
  // post lock book
  await api.lockBook(bookId);
}
async function solveRiddle() {
  await api.postSolved(riddle.value.riddleId);
}

async function goNext() {
  router.push(`/book/${bookId}/riddle/view/${parseInt(riddlePos) + 1}`);
}
onMounted(async () => {
  if (route.params.id == "finish") {
    finished.value = true;
  } else {
    console.log("mounted else");

    isBookLocked();
  }
});
</script>
