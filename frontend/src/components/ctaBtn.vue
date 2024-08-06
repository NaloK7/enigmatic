<template>
  <RouterLink
    :to="link"
    :class="[width]"
    class="flex justify-center items-center font-audiowide mx-4 w-40 h-8 rounded-lg bg-secondaryPink border-2 border-primaryPink hover:border-secondaryPink hover:bg-primaryPink text-lg hover:text-black">
    {{ text }}</RouterLink
  >
</template>

<script setup>
defineProps({
  text: String,
  link: String,
  width: "w-44",
});
</script>
