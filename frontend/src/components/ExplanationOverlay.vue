<template>
  <div
    v-if="display"
    class="bg-gray-950/50 fixed top-0 left-0 w-full h-full p-4 flex justify-center align-middle z-50">
    <div
      class="flex flex-col justify-between bg-black m-auto h-min p-4 gap-2 rounded-lg text-gray-200 border border-primaryPink">
      <div class="grid grid-cols-3">
        <span class="text-center col-start-2 text-lg">!! Bravo !!</span>
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
      <span v-if="text && text != ''"><u>Explication:</u></span>
      <p v-html="text" class="px-4"></p>
      <button
        class="font-audiowide mx-auto w-40 h-8 rounded-lg bg-darkPink border-2 border-primaryPink hover:border-darkPink hover:bg-primaryPink text-lg hover:text-black"
        @click="emitNext()">
        <!-- emit next to reset page for query next riddle -->
        suivant
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiCloseBox } from "@mdi/js";

defineProps({
  text: String,
  display: Boolean,
});

const closeIco = ref(mdiCloseBox);

const emit = defineEmits(["closeOverlay", "next"]);

function emitCloseEvent() {
  emit("closeOverlay");
}
function emitNext() {
  emit("next");
}
</script>
