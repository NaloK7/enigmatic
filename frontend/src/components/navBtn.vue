<template>
  <button @click="redirectToRiddle">{{ text }}</button>
</template>

<script setup>
import apiEnigm from "@/router/interceptor";

import { useRouter } from "vue-router";
import { ref } from "vue";
const router = useRouter();
const props = defineProps({
  text: String,
  section: String,
});
const position = ref(0);
const title = ref("");
const wording = ref("");
const explanation = ref("");

async function redirectToRiddle() {
  await getLastRiddle();
  router.push(`/book/${props.section}/riddle/view/${position.value}`);
}
async function getLastRiddle() {
  const xhr = await apiEnigm.post("?action=last", {
    bookId: props.section,
  });
  const response = await xhr;
  if (response.status == 200) {
    title.value = response.data["title"];
    wording.value = response.data["wording"];
    position.value = response.data["position"];
    explanation.value = response.data["explanation"];
  } else {
    console.log(response.status);
  }
}
</script>
<style scoped>
button {
  display: block;
  margin: 17px;
  height: 28px;
  width: 92px;
  border: 2px solid #17ae9f;
  border-radius: 5px;
  background: #18544e;
  color: white;
  font-family: "Audiowide";
  text-align: center;
  line-height: 1.4em;
}
button:hover {
  border: 2px solid #18544e;
  background: #17ae9f;
  color: black;
}
</style>
