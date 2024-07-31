<template>
  <button @click="showPopup()">Show Popup</button>

  <!-- Overlay and Popup -->
  <div
    id="overlay"
    class="overlay fixed top-0 left-0 w-full h-full p-4 flex justify-center align-middle z-50">
    <div
      class="flex bg-black min-h-56 h-auto p-4 rounded-lg text-gray-200 border border-primaryPink">
      <p>This is a centered popup!</p>
      <button
        class="font-audiowide mx-4 w-40 h-8 rounded-lg bg-darkPink border-2 border-primaryPink hover:border-darkPink hover:bg-primaryPink text-lg hover:text-black"
        @click="closePopup()">
        suivant
      </button>
    </div>
  </div>
</template>

<script setup>
function showPopup() {
  document.getElementById("overlay").style.display = "flex";
}

function closePopup() {
  document.getElementById("overlay").style.display = "none";
}
</script>

<style scoped>
/* Overlay */
.overlay {
  background: rgba(0, 0, 0, 0.5);
  display: none;
}
</style>
