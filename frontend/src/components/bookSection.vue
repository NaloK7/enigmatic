<template>
  <span class="dark-glass rounded-md py-4">
    <table class="w-full text-center">
      <tr>
        <td class="text-primaryPink text-shadow-pink font-semibold text-xl">
          {{ bookTitle }}
        </td>
      </tr>
      <div class="my-2 mx-auto w-3/5 border-b border-primaryGreen"></div>
      <tr v-for="element in bookData" :key="element.id">
        <td class="text-gray-200">
          <!-- todo review style -->
          <!-- if riddle solved → user_id != null  -->
          <RouterLink
            v-if="element.user_id != null"
            class="block hover:text-primaryPink font-medium text-lg text-primaryGreen"
            :to="`/book/${element.section_id}/riddle/view/${element.position}`">
            {{ element.title }}
          </RouterLink>
          <button
            v-else
            class="font-medium text-lg text-gray-400"
            @click="getLastRiddlePos(element.section_id)">
            {{ element.title }}
          </button>
        </td>
      </tr>
    </table>
  </span>
</template>

<script setup>
import { RouterLink, useRouter } from "vue-router";
import api from "@/composables/api";

const router = useRouter();
const props = defineProps({
  bookTitle: String,
  bookData: Array,
});

async function getLastRiddlePos(bookId) {
  const response = await api.getLast("last", bookId);
  // console.log("🚀 ~ getLastRiddlePos ~ response:", response);

  if (response.status == 200) {
    // failed.value = false;
    let lastId = response.data.position;
    // console.log("🚀 ~ getLastRiddlePos ~ lastId:", lastId);
    router.push(`/book/${bookId}/riddle/view/${lastId}`);
  } else {
    console.log(response.status);
  }
}
</script>
