<template>
  <div class="flex flex-col dark-glass glass-border">
    <div class="mx-auto w-1/3 p-6 relative">
      <div class="mx-auto flex flex-col justify-center items-center space-y-2">
        <h1 class="text-2xl font-medium text-gray-200">connexion</h1>
      </div>
      <form class="w-full mt-6 space-y-6">
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
          class="w-full justify-center text-white font-audiowide text-lg border-2 border-primaryPink bg-darkPink hover:border-darkPink hover:bg-primaryPink hover:text-black active:text-white active:bg-darkPink rounded-lg"
          name="login"
          type="submit"
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
          <!-- todo see how forgotten password works -->
          <RouterLink class="text-blue-500 font-medium hover:underline" to="#"
            >mot de passe oublié ?</RouterLink
          >
        </div>
      </form>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "@/composables/api";
import { setToken } from "@/stores/tokenStore";
const router = useRouter();

const email = ref("");
const password = ref("");

async function connect() {
  try {
    const xhr = await api.getUser(`login`, email.value, password.value);

    const response = await xhr;

    if (response.status == 200) {
      // failed.value = false;
      setToken(response.data["token"]);
      router.push({ name: "home" });
    }
  } catch (error) {
    console.log("An error as occurred:", error.response.status);
  }
}
</script>
