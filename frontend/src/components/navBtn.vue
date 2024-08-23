<template>
  <button
    :class="{ 'border-primaryPink hover:border-secondaryPink': finished }"
    class="block w-1/4 md:min-w-24 h-8 md:my-5 md:h-7 md:w-24 border md:border-2 border-primaryGreen sm:before:content-['Livre\00a0'] md:rounded-md bg-secondaryGreen text-white font-audiowide hover:text-black hover:bg-primaryGreen hover:border-secondaryGreen"
    @click="redirectToLast(section)">
    {{ text }}
  </button>
</template>

<script setup>
import api from "@/composables/api";

import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
const router = useRouter();
const props = defineProps({
  text: String,
  section: String,
});

const finished = ref(false);

async function redirectToLast(bookId) {
  const response = await api.getLast(bookId);

  if (response.status == 200) {
    // failed.value = false;
    let lastId = response.data.position;
    router.push(`/book/${bookId}/riddle/view/${lastId}`);
  } else if (response.status == 204) {
    router.push(`/book/${bookId}/riddle/view/finish`);
  } else {
    console.log(response.status);
  }
}

onMounted(async () => {
  const xhrFinish = await api.isFinished(props.section);
  finished.value = xhrFinish.data;
});
</script>
