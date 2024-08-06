<template>
  <button
    class="block m-5 h-7 w-24 border-2 border-primaryGreen rounded-md bg-secondaryGreen text-white font-audiowide hover:text-black hover:bg-primaryGreen hover:border-secondaryGreen"
    @click="redirectToLast(section)">
    {{ text }}
  </button>
</template>

<script setup>
import api from "@/composables/api";

import { useRouter } from "vue-router";
import { ref } from "vue";
const router = useRouter();
const props = defineProps({
  text: String,
  section: String,
});

async function redirectToLast(bookId) {
  const response = await api.getLast(bookId);

  if (response.status == 200) {
    // failed.value = false;
    let lastId = response.data.position;
    router.push(`/book/${bookId}/riddle/view/${lastId}`);
  } else {
    console.log(response.status);
  }
}
</script>
