<template>
  <section class="dark-glass glass-border">
    <div class="mx-auto w-full px-4 sm:w-3/4 md:w-1/2 xl:w-1/3 p-6">
      <h1 class="text-center text-2xl font-medium text-gray-200">connexion</h1>
      <form class="w-full mt-6 space-y-6">
        <!-- feedback failed -->
        <span
          v-if="failed"
          class="block text-red-500 text-center text-xl font-semibold"
          >! un problème est survenue veuillez&nbspréessayé !</span
        >
        <div>
          <input
            class="outline-none border-2 rounded-lg px-2 py-1 text-slate-500 w-full focus:border-blue-300"
            v-model="email"
            placeholder="email"
            id="email"
            name="email"
            type="text" />
        </div>
        <div>
          <input
            class="outline-none border-2 rounded-lg px-2 py-1 text-slate-500 w-full focus:border-blue-300"
            v-model="password"
            placeholder="Mot de passe"
            id="password"
            name="password"
            type="password" />
        </div>

        <button
          class="w-full justify-center font-audiowide text-white text-lg rounded-lg border-2 bg-secondaryPink border-primaryPink hover:border-secondaryPink hover:bg-primaryPink hover:text-black active:border-primaryPink active:bg-secondaryPink active:text-white"
          name="login"
          @click.prevent="connect()">
          valider
        </button>

        <div class="flex justify-between space-x-1">
          <div>
            <span class="text-slate-700"> Pas de compte ? </span>
            <RouterLink class="text-blue-500 hover:underline" to="/inscription"
              >inscription
            </RouterLink>
          </div>
          <RouterLink
            class="text-blue-500 font-medium hover:underline"
            to="/forget"
            >mot de passe oublié&nbsp?</RouterLink
          >
        </div>
      </form>
    </div>
  </section>
</template>
<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "@/composables/api";
import { setToken } from "@/stores/tokenStore";
const router = useRouter();

const email = ref("");
const password = ref("");
const failed = ref(false);

async function connect() {
  try {
    const criteria = { email: email.value, password: password.value };
    const response = await api.postOne(`login`, criteria);
    if (response.status == 200) {
      failed.value = false;
      setToken(response.data["token"]);
      router.push({ name: "home" });
    } else {
      failed.value = true;
    }
  } catch (error) {
    failed.value = true;
    alert(error);
    console.log("An error as occurred:", error.response.status);
  }
}
</script>
