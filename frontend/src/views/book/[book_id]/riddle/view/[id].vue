<template>
  <section class="dark-glass my-10" id="riddle-glass">
    <div
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
  </section>
</template>

<script setup>
import apiEnigm from "@/router/interceptor";

const riddle = ref([]);

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
