<template>
  <section class="dark-glass glass-border">
    <div class="mx-auto w-full px-4 sm:w-3/4 md:w-1/2 xl:w-1/3 p-6">
      <h1 v-if="!sended" class="text-center text-2xl font-medium text-gray-200">
        Mot de passe oublié
      </h1>
      <span v-else class="block text-center text-2xl font-medium text-gray-200"
        >Vérifiez vos emails pour réinitialiser votre mot de passe</span
      >

      <form class="w-full mt-6 space-y-6">
        <!-- feedback failed -->
        <span
          v-if="failed"
          class="block text-red-500 text-center text-xl font-semibold"
          >! un problème est survenue veuillez réessayé !</span
        >
        <!-- EMAIL FIELD -->
        <div>
          <!-- feedback wrong email format -->
          <div v-if="!emailValid" class="text-red-500 text-center">
            email@exemple.com
          </div>
          <input
            :class="{ 'border-red-500': !emailValid }"
            class="outline-none border-2 rounded-lg px-2 py-1 text-slate-500 w-full focus:border-blue-300"
            v-model="email"
            @focus="emailValid = true"
            placeholder="email"
            name="email"
            type="text" />
        </div>

        <!-- SUBMIT BUTTON -->
        <button
          class="w-full justify-center text-white font-audiowide text-lg border-2 border-primaryPink bg-secondaryPink hover:border-secondaryPink hover:bg-primaryPink hover:text-black active:text-white active:bg-secondaryPink rounded-lg"
          id="login"
          name="login"
          type="submit"
          @click.prevent="sendMail()">
          valider
        </button>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref } from "vue";
import { useEmailRule } from "../../composables/rules.js";
import api from "@/composables/api";

const email = ref("");
const emailValid = ref(true);
const sended = ref(false);
const failed = ref(false);

async function sendMail() {
  if (formRules()) {
    const criteria = { email: email.value };
    const xhr = await api.getOne("forget", criteria);
    const response = await xhr;
    // console.log(response);

    // display msg
    sended.value = true;
  }
}

function formRules() {
  emailValid.value = useEmailRule(email.value);
  return emailValid.value;
}
</script>
