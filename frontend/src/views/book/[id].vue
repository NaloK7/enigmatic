<template>
  <h1
    class="dark-glass border-y border-primaryPink text-center text-gray-200 mb-6 py-4">
    Progression
  </h1>
  <div class="grid grid-cols-4 gap-4 text-center">
    <bookSection :bookTitle="'Livre I'" :bookData="books[0]" />
    <bookSection :bookTitle="'Livre II'" :bookData="books[1]" />
    <bookSection :bookTitle="'Livre III'" :bookData="books[2]" />
    <bookSection :bookTitle="'Livre IV'" :bookData="books[3]" />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import apiEnigm from "@/router/interceptor";
import { useRouter } from "vue-router";
import bookSection from "@/components/bookSection.vue";

const router = useRouter();

const books = ref([]);

async function getAllRiddles() {
  const xhr = await apiEnigm.post("?action=books");
  const response = await xhr;
  if (response.status == 200) {
    books.value = response.data;
  } else {
    console.log(response.status);
  }
}
onMounted(async () => {
  await getAllRiddles();
});
</script>
