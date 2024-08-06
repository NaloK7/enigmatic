<template>
  <div
    v-if="display"
    class="bg-gray-950/50 fixed top-0 left-0 w-full h-full p-4 flex justify-center align-middle z-50">
    <div
      class="flex flex-col justify-between bg-black m-auto h-min p-4 gap-2 rounded-lg text-gray-200 border border-primaryPink">
      <div class="grid grid-cols-3">
        <span class="text-center col-start-2 text-lg">!! Attention !!</span>
        <!-- emit display = false -->
        <button
          @click="emitCloseEvent()"
          class="w-min ml-auto -mt-2 -mr-2 text-primaryGreen hover:text-primaryPink active:text-primaryGreen">
          <svg-icon
            width="30"
            height="30"
            type="mdi"
            :path="closeIco"
            class="inline-block">
          </svg-icon>
        </button>
      </div>
      <!-- todo review text explanation -->
      <span v-if="answer == ''" class="text-center col-start-2 text-lg mb-2"
        >"abandoner" bloquera ce livre pendant 1 mois entier.<br />
        Vous y aurez de nouveau accès seulement une fois ce temps écoulé.</span
      >
      <div v-else class="space-y-2">
        <span v-if="answer && answer != ''"
          >la réponse était: {{ answer }}</span
        >
        <p v-if="text && text != ''">
          <u>Explication:</u><br /><span v-html="text" class="px-4"></span>
        </p>
      </div>
      <!--  -->
      <div class="flex justify-evenly">
        <button
          class="inline-block font-audiowide w-40 h-8 rounded-lg bg-secondaryGreen border-2 border-primaryGreen hover:border-secondaryGreen hover:bg-primaryGreen text-lg hover:text-black"
          @click="emitCloseEvent()">
          <!-- emit next to reset page for query next riddle -->
          retour
        </button>
        <button
          v-if="answer == ''"
          class="inline-block font-audiowide w-40 h-8 rounded-lg bg-secondaryPink border-2 border-primaryPink hover:border-secondaryPink hover:bg-primaryPink text-lg hover:text-black"
          @click="emitGiveUp()">
          <!-- emit next to reset page for query next riddle -->
          abandonner
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiCloseBox } from "@mdi/js";

defineProps({
  display: Boolean,
  answer: String,
  text: String,
});

const closeIco = ref(mdiCloseBox);

const emit = defineEmits(["closeOverlay", "giveUp"]);

function emitCloseEvent() {
  emit("closeOverlay");
}
function emitGiveUp() {
  emit("giveUp");
}
</script>
