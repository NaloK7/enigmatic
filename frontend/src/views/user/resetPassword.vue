<template>
  <section class="dark-glass glass-border">
    <div class="mx-auto w-full px-4 sm:w-3/4 md:w-1/2 xl:w-1/3 p-6">
      <h1 class="text-center text-2xl font-medium text-gray-200">
        nouveau mot de passe
      </h1>

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
        <!-- SUBMIT BUTTON -->
        <button
          class="w-full justify-center text-white font-audiowide text-lg border-2 border-primaryPink bg-secondaryPink hover:border-secondaryPink hover:bg-primaryPink hover:text-black active:text-white active:bg-secondaryPink rounded-lg"
          id="login"
          name="login"
          type="submit"
          @click.prevent="newPassword()">
          valider
        </button>
        <div class="flex justify-between space-x-1">
          <span class="text-slate-700">
            déjà inscrit ?
            <RouterLink class="text-blue-500 hover:underline" to="/login"
              >connexion
            </RouterLink></span
          >
          <RouterLink class="text-blue-500 hover:underline" to="/legalNotice"
            >Mentions légales
          </RouterLink>
        </div>
      </form>
    </div>
  </section>
</template>
<script setup>
import { ref } from "vue";
import { useEmailRule, usePasswordRule } from "../../composables/rules.js";
import { setToken } from "@/stores/tokenStore";
import { useRoute, useRouter, RouterLink } from "vue-router";
import { jwtDecode } from "jwt-decode";
import api from "@/composables/api";

const router = useRouter();
const route = useRoute();
const token = route.query.token;
const decoded = jwtDecode(token);
const tokenId = decoded.user_id;
const tokenEmail = decoded.email;

const email = ref("");
const emailValid = ref(true);

const password = ref("");
const passwordValid = ref(true);

const confirmPass = ref("");
const confirmPassValid = ref(true);
const failed = ref(false);

async function newPassword() {
  if (formRules()) {
    try {
      const criteria = {
        userId: tokenId,
        email: tokenEmail,
        password: password.value,
        token: token,
      };
      const xhr = await api.updateOne(`updateUser`, criteria);

      const response = await xhr;
      if (response["status"] == 200) {
        failed.value = false;
        setToken(response.data["token"]);
        router.push({ name: "home" });
      } else if (response["status"] == 202) {
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
