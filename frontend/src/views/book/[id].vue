<template>
  <!-- books -->
  <!-- 4 col -->
  <!-- liste of riddles -->
  <h1 class="dark-glass text-center text-gray-200 mb-4 py-4">Progression</h1>
  <div class="grid grid-cols-4 gap-4 text-center">
    <span class="dark-glass rounded-md" col-1>
      <table class="w-full text-center">
        <tr>
          <td :class="titleBook">Livre 1</td>
        </tr>
        <tr v-for="element in bookOne">
          <td :class="titleRiddle">{{ element.title }}</td>
        </tr>
      </table>
    </span>
    <span class="dark-glass rounded-md" col-2>
      <table class="w-full text-center">
        <tr>
          <td :class="titleBook">Livre 2</td>
        </tr>
        <tr v-for="element in bookTwo">
          <td :class="titleRiddle">{{ element.title }}</td>
        </tr>
      </table>
    </span>
    <span class="dark-glass rounded-md" col-3>
      <table class="w-full text-center">
        <tr>
          <td :class="titleBook">Livre 3</td>
        </tr>
        <tr v-for="element in bookThree">
          <td :class="titleRiddle">{{ element.title }}</td>
        </tr>
      </table>
    </span>
    <span class="dark-glass rounded-md" col-4>
      <table class="w-full text-center">
        <tr>
          <td :class="titleBook">Livre 4</td>
        </tr>
        <tr v-for="element in bookFour">
          <td :class="titleRiddle">{{ element.title }}</td>
        </tr>
      </table>
    </span>
  </div>
</template>

<script setup>
import { ref } from "vue";
import apiEnigm from "@/router/interceptor";
import { useRouter } from "vue-router";
const router = useRouter();

const bookOne = ref([]);
const bookTwo = ref([]);
const bookThree = ref([]);
const bookFour = ref([]);

const titleBook = ref("text-primaryPink font-semibold text-lg");
const titleRiddle = ref("text-gray-200");

async function getAllRiddles() {
  const xhr = await apiEnigm.post("?action=books");
  const response = await xhr;
  if (response.status == 200) {
    response.data.forEach((element) => {
      switch (element["section_id"]) {
        case 1:
          bookOne.value.push(element);
          break;
        case 2:
          bookTwo.value.push(element);
          break;
        case 3:
          bookThree.value.push(element);
          break;
        case 4:
          bookFour.value.push(element);
          break;
        default:
          console.log("Unknown section_id:", element["section_id"]);
      }
    });
  } else {
    console.log(response.status);
  }
}
getAllRiddles();
</script>
