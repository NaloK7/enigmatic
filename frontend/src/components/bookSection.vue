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
          <!-- finish white -->
          <!-- last green -->
          <!-- next grey (not a link) -->
          <RouterLink
            v-if="element.user_id != null"
            class="block hover:text-primaryPink font-medium text-lg text-primaryGreen"
            :to="`/book/${element.section_id}/riddle/view/${element.position}`">
            {{ element.title }}
          </RouterLink>
          <RouterLink
            v-else
            class="block hover:text-gray-300 font-medium text-lg text-gray-300"
            :to="`/book/${element.section_id}/riddle/view/all`">
            {{ element.title }}
          </RouterLink>
        </td>
      </tr>
    </table>
  </span>
</template>

<script setup>
import { RouterLink } from "vue-router";

const props = defineProps({
  bookTitle: String,
  bookData: Array,
});
</script>
