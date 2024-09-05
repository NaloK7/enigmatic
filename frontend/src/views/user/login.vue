<template>
  <section class="dark-glass glass-border">
    <div class="mx-auto w-full px-4 sm:w-3/4 md:w-1/2 xl:w-1/3 p-6">
      <h1 class="text-center text-2xl font-medium text-gray-200">connexion</h1>
      <form class="w-full mt-6 space-y-6">
        <!-- feedback failed -->
        <span
          v-if="failed"
          class="block text-red-500 text-center text-xl font-semibold"
          >! un problème est survenue veuillez réessayé !</span
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
          class="w-full justify-center text-white font-audiowide text-lg border-2 border-primaryPink bg-secondaryPink md:hover:border-secondaryPink md:hover:bg-primaryPink md:hover:text-black active:text-white active:bg-secondaryPink rounded-lg"
          name="login"
          type="submit"
          @click.prevent="connect()"
          @touchend.prevent="connect()">
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
            >mot de passe oublié ?</RouterLink
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
    const response = await api.getOne(`login`, criteria);

    if (response.status == 200) {
      failed.value = false;
      setToken(response.data["token"]);
      router.push({ name: "home" });
    } else {
      failed.value = true;
    }
  } catch (error) {
    console.log("An error as occurred:", error.response.status);
  }
}
</script>
