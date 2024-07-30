<template>
  <section class="dark-glass border-y border-primaryGreen my-10">
    <div
      v-if="!blocked"
      class="flex flex-col justify-start items-center w-3/5 px-10 py-0 mx-auto h-[367px]">
      <h2
        class="font-audiowide text-xl p-0 py-10 text-primaryPink text-shadow-pink">
        L'énigme d'Einstein
      </h2>

      <p class="riddle-txt">
        Cette énigme fut posée aux étudiants de l'université de Stanford lors
        d'une épreuve de réflexion.<br /><br />- C'est mieux que Dieu.<br />-
        C'est pire que le Diable.<br />- Les pauvres en ont.<br />- Les riches
        en ont besoin.<br />- Et si on en mange, on meurt.<br /><br /><b
          >Qu'est ce que c'est ?</b
        >
      </p>
    </div>
    <div v-else class="mx-auto w-3/5 my-4 space-y-4">
      <span class="block text-primaryPink text-shadow-pink text-center text-xl"
        >Bloqué</span
      >
      <span class="block text-center text-lg text-gray-200"
        >Ce livre est bloqué pendant encore <b>{{ dayDifference }}</b> jours
        <br />Il sera debloqué le {{ expirationDate }}</span
      >
      <div
        class="px-2 mx-auto w-2/3 flex items-center justify-evenly rounded-b-xl">
        <navBtn to="/book/1/riddle/view/all" text="Livre I"></navBtn>
        <navBtn to="/book/2/riddle/view/all" text="Livre II"></navBtn>
        <navBtn to="/book/3/riddle/view/all" text="Livre III"></navBtn>
        <navBtn to="/book/4/riddle/view/all" text="Livre IV"></navBtn>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import apiEnigm from "@/router/interceptor";
import navBtn from "@/components/navBtn.vue";

const route = useRoute();
const bookId = route.params.book_id;

const blocked = ref(false);
const expirationDate = ref();
const dayDifference = ref();

const riddle = ref([]);
// check if section is blocked
async function isBlocked() {
  const xhr = await apiEnigm.post("?action=blocked", {
    bookId: bookId,
  });

  const response = await xhr;
  if (response.status == 200) {
    // query last riddle
  } else if (response.status == 202) {
    blocked.value = true;
    expirationDate.value = new Date(response.data["expiration"]);
    const currentDate = new Date();

    const timeDifference = expirationDate.value - currentDate;
    // Convert the time difference from milliseconds to days
    dayDifference.value = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
    expirationDate.value = expirationDate.value.toLocaleDateString("fr-FR");
  }
}
onMounted(async () => {
  await isBlocked();
});
// params.id = last
//      get last position unsolved
// params.id = int
//      get riddle with this position

// SELECT r.position, r.title, r.wording
// FROM riddle r
// LEFT JOIN solve s ON r.id = s.riddle_id AND s.user_id = 16
// WHERE s.riddle_id IS NULL
// AND r.section_id = 1
// ORDER BY r.position ASC
// LIMIT 1;
</script>
