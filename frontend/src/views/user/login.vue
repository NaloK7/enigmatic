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
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <!-- todo see how remember work in detail -->
            <input
              class="mr-2 w-4 h-4"
              v-model="remember"
              name="remember"
              type="checkbox" />
            <span class="text-slate-500">se souvenir de moi</span>
          </div>
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
import axios from "axios";
const router = useRouter();

const email = ref("");
const password = ref("");
const remember = ref(false);

async function connect() {
  try {
    // todo make a function/composable to fetch: base_url / params
    const response = await axios.get(
      "http://localhost/enigmatic/backend/index.php?action=login",
      {
        email: email.value,
        password: password.value,
        remember: remember.value,
      }
    );

    const data = await response;
    if (response["status"] == 200) {
      console.log("connected");
      // failed.value = false;
      localStorage.setItem("token", response["token"]);
      router.push({ name: "home" });
    } else if (response["status"] >= 400) {
      console.log("NOT connected");
      // failed.value = true;
    }
  } catch (error) {
    console.error("Error calling PHP function:", error);
  }
}
</script>
