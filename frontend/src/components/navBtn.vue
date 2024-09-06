<template>
  <button
    :class="{ 'border-primaryPink hover:border-secondaryPink': finished }"
    class="block w-1/4 h-10  md:min-w-24md:h-7 md:my-5 md:w-24 border md:border-2 border-primaryGreen sm:before:content-['Livre\00a0'] md:rounded-md bg-secondaryGreen text-white font-audiowide hover:text-black hover:bg-primaryGreen hover:border-secondaryGreen"
    @click="redirectToLast(section)">
    {{ text }}
  </button>
</template>

<script setup>
import api from "@/composables/api";

import { useRouter } from "vue-router";
import { ref, onMounted, nextTick } from "vue";
const router = useRouter();
const props = defineProps({
  text: String,
  section: String,
});

const finished = ref(false);

async function redirectToLast(bookId) {
  const response = await api.getOne("last", { bookId });

  if (response.status == 200 || response.status == 204) {
    let lastId = response.data.position ? response.data.position : "finished";

    router.push(`/book/${bookId}/riddle/view/${lastId}`);
  } else {
    console.log(response.status);
  }
}

onMounted(async () => {
  if (localStorage.getItem("token")) {
    const criteria = { bookId: props.section };
    const xhrFinish = await api.getOne("finish", criteria);
    finished.value = xhrFinish.data;
  }
});
</script>
