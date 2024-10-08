<template>
  <section v-if="finished" class="dark-glass border-y border-primaryGreen mb-4">
    <span class="block p-4 text-center text-gray-200"
      >Bravo!!<br />Vous avez résolut toutes les énigmes de ce livre.</span
    >
    <div
      class="px-2 mx-auto hidden items-center justify-evenly rounded-b-xl md:flex lg:w-1/2">
      <navBtn section="1" text="I"></navBtn>
      <navBtn section="2" text="II"></navBtn>
      <navBtn section="3" text="III"></navBtn>
      <navBtn section="4" text="IV"></navBtn>
    </div>
  </section>

  <section
    v-if="riddlePos != 'finished'"
    class="dark-glass border-y border-primaryGreen">
    <!-- RIDDLE -->
    <div
      v-if="!blocked && riddle"
      class="flex flex-col justify-start items-center w-11/12 sm:w-4/5 lg:w-2/3 lg:px-10 py-6 space-y-4 mx-auto">
      <span
        class="pb-4 font-audiowide text-xl md:text-2xl text-center text-primaryPink md:text-shadow-pink block md:inline-block">
        {{ riddle.section_id }}-{{ riddle.position }}.
        <h1 class="block md:inline-block text-2xl">{{ riddle.title }}</h1>
      </span>
      <p class="riddle-txt text-gray-200" v-html="riddle.wording"></p>

      <div
        class="mt-auto block sm:h-8 w-full md:grid"
        style="grid-template-columns: 1fr 2fr 1fr">
        <div
          :class="{
            'animate-shake border border-red-500 rounded-lg': badAnswer,
          }"
          class="flex flex-col md:h-8 space-y-2 sm:col-start-2 sm:space-y-0 sm:flex-row">
          <input
            type="text"
            v-model="answer"
            name="answer"
            @keydown.enter="checkAnswer()"
            id="answer"
            placeholder="réponse"
            class="rounded-lg w-full h-8 sm:w-96 pl-2 border border-gray-500 bg-gray-200 text-gray-800 focus:outline-none" />
          <button
            class="rounded-lg w-full h-8 sm:w-16 font-semibold border bg-gray-200 border-gray-500 text-gray-800 hover:bg-primaryGreen hover:text-white"
            @click="checkAnswer()">
            Valider
          </button>
        </div>
        <button
          class="sm:col-start-3 mt-2 sm:mt-0 sm:ml-4 rounded-lg w-full h-8 sm:w-16 sm:h-full font-semibold border bg-gray-200 border-gray-500 text-gray-800 hover:bg-primaryGreen hover:text-white"
          @click="showOverlay('giveUp')">
          Passer
        </button>
      </div>
    </div>

    <!-- BLOCKED FEEDBACK -->
    <div v-else-if="blocked" class="mx-auto w-4/5 md:w-3/5 my-4 space-y-4">
      <span class="block text-primaryPink text-shadow-pink text-center text-xl"
        >Bloqué</span
      >
      <span class="block text-center text-lg text-gray-200"
        >Ce livre est bloqué pendant: <b>{{ dayDifference }}</b> jours <br />Il
        sera debloqué le {{ expirationDate }}</span
      >
      <div
        v-if="!finished"
        class="px-2 mx-auto hidden md:flex items-center justify-evenly rounded-b-xl">
        <navBtn section="1" text="I"></navBtn>
        <navBtn section="2" text="II"></navBtn>
        <navBtn section="3" text="III"></navBtn>
        <navBtn section="4" text="IV"></navBtn>
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
  const xhrFinish = await api.getOne("finish", { bookId });
  if (xhrFinish.data) {
    finished.value = true;
  }

  const response = await api.getOne("isLocked", { bookId });
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

async function getOneRiddle() {
  const response = await api.getOne("riddle", { bookId, riddlePos });

  if (response.status == 200) {
    riddle.value = response.data;
  } else {
    console.log(response.status);
  }
}

async function checkAnswer() {
  if (answer.value != "") {
    const criteria = {
      riddleId: riddle.value.riddleId,
      answer: answer.value,
    };
    const response = await api.getOne("checkAnswer", criteria);

    if (response.status == 200) {
      solveRiddle();
      // popup explanation
      const criteria = { riddleId: riddle.value.riddleId };
      const response = await api.getOne("explanation", criteria);
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
  const criteria = { riddleId: riddle.value.riddleId };
  const xhrSolution = await api.getOne("getAnswer", criteria);
  answer.value = xhrSolution.data.solution;
  // get explanation
  const xhrExplanation = await api.getOne("explanation", criteria);
  explanation.value = xhrExplanation.data.explanation;
  // post solve
  solveRiddle();
  // post lock book
  await api.postOne("lockBook", { bookId });
}

async function solveRiddle() {
  const criteria = { riddleId: riddle.value.riddleId };
  await api.postOne("solve", criteria);
}

async function goNext() {
  const xhrFinish = await api.getOne("finish", { bookId });
  if (xhrFinish.data) {
    finished.value = true;
    router.push(`/book/${bookId}/riddle/view/finished`);
    // update the header
  } else {
    router.push(`/book/${bookId}/riddle/view/${parseInt(riddlePos) + 1}`);
  }
}
onMounted(async () => {
  isBookLocked();
});
</script>
