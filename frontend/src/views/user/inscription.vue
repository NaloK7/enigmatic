<template>
  <div class="flex flex-col dark-glass glass-border">
    <div class="mx-auto w-1/3 p-6 relative">
      <div class="mx-auto flex flex-col justify-center items-center space-y-2">
        <h1 class="text-2xl font-medium text-gray-200">inscription</h1>
      </div>
      <form class="w-full mt-6 space-y-6">
        <div>
          <input
            class="outline-none border-2 rounded-lg px-2 py-1 text-slate-500 w-full focus:border-blue-300"
            v-model="email"
            placeholder="email"
            name="email"
            type="text" />
        </div>
        <div>
          <input
            class="outline-none border-2 rounded-lg px-2 py-1 text-slate-500 w-full focus:border-blue-300"
            v-model="password"
            placeholder="Mot de passe"
            name="password"
            type="password" />
        </div>
        <div>
          <input
            class="outline-none border-2 rounded-lg px-2 py-1 text-slate-500 w-full focus:border-blue-300"
            v-model="confirmPass"
            placeholder="Confirmation"
            name="confirmPass"
            type="password" />
        </div>
        <div class="flex items-center justify-between"></div>
        <!-- todo prevent submit with rules: regex email & password -->
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

const email = ref("test");
const password = ref("test");
const confirmPass = ref("test");

async function inscription() {
  if (
    email.value != "" &&
    password.value != "" &&
    password.value === confirmPass.value
  ) {
    const response = await fetch(
      "http://enigmatic.test/index.php?action=inscription",
      {
        method: "POST",
        body: JSON.stringify({
          email: email.value,
          password: password.value,
        }),
      }
    );

    const data = await response;
    console.log(data);
  }
  // else password not the same feedback
}
</script>
