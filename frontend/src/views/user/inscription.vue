<template>
  <div class="flex flex-col dark-glass glass-border">
    <div class="mx-auto w-1/3 p-6 relative">
      <div class="mx-auto flex flex-col justify-center items-center space-y-2">
        <h1 class="text-2xl font-medium text-gray-200">inscription</h1>
      </div>
      <form class="w-full mt-6 space-y-6">
        <!-- feedback failed query -->
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
        <!-- PASSWORD FIELD -->
        <div>
          <!-- feedback wrong password format -->
          <div v-if="!passwordValid" class="text-red-500 text-center">
            12 caractères minimum, majuscules, minuscules, chiffres et
            caractères spéciaux
          </div>
          <input
            :class="{ 'border-red-500': !passwordValid }"
            @focus="passwordValid = true"
            class="outline-none border-2 rounded-lg px-2 py-1 text-slate-500 w-full focus:border-blue-300"
            v-model="password"
            placeholder="Mot de passe"
            name="password"
            type="password" />
        </div>
        <!-- CONFIRMATION PASSWORD -->
        <div>
          <!-- feedback confirmation password -->
          <div v-if="!confirmPassValid" class="text-red-500 text-center">
            mot de passe différent
          </div>
          <input
            :class="{ 'border-red-500': !confirmPassValid }"
            @focus="confirmPassValid = true"
            class="outline-none border-2 rounded-lg px-2 py-1 text-slate-500 w-full focus:border-blue-300"
            v-model="confirmPass"
            placeholder="Confirmation"
            name="confirmPass"
            type="password" />
        </div>
        <div class="flex items-center justify-between"></div>
        <!-- SUBMIT BUTTON -->
        <button
          class="w-full justify-center text-white font-audiowide text-lg border-2 border-primaryPink bg-darkPink hover:border-darkPink hover:bg-primaryPink hover:text-black active:text-white active:bg-darkPink rounded-lg"
          id="login"
          name="login"
          type="submit"
          @click.prevent="inscription()">
          valider
        </button>
        <div class="flex justify-center space-x-1">
          <span class="text-slate-700"> déjà inscrit ? </span>
          <RouterLink class="text-blue-500 hover:underline" to="/login"
            >connexion
          </RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";
import { useEmailRule, usePasswordRule } from "../../composables/rules.js";
import axios from "axios";

const email = ref("email@test.fr");
const emailValid = ref(true);

const password = ref("P@ssw0rd!2024");
const passwordValid = ref(true);

const confirmPass = ref("P@ssw0rd!2024");
const confirmPassValid = ref(true);

const failed = ref(false);

async function inscription() {
  if (formRules()) {
    try {
      const xhr = await axios.post(
        "http://localhost/enigmatic/backend/index.php?action=inscription",
        {
          email: email.value,
          password: password.value,
        }
      );
      // const response = await fetch(
      //   "http://localhost/enigmatic/backend/index.php?action=inscription",
      //   {
      //     method: "POST",
      //     body: JSON.stringify({
      //       email: email.value,
      //       password: password.value,
      //     }),
      //   }
      // );

      const response = await xhr.data;
      console.log(response);
      if (response["status"] == 200) {
        // connect the user
        // fetch action=login
        // set JWT token in cookies
      } else if (response["status"] >= 400) {
        failed.value = true;
      }
    } catch (error) {
      console.log("inscription ~ error:", error);
    }
  }
}

function formRules() {
  emailValid.value = useEmailRule(email.value);
  passwordValid.value = usePasswordRule(password.value);
  confirmPassValid.value = password.value == confirmPass.value;
  return emailValid.value && passwordValid.value && confirmPassValid.value;
}
</script>
