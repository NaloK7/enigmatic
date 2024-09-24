<template>
  <RouterLink
    :to="link"
    class="flex justify-center items-center text-lg font-audiowide mx-4 w-40 h-8 rounded-lg bg-secondaryPink border-2 border-primaryPink hover:border-secondaryPink hover:bg-primaryPink hover:text-black active:border-secondaryPink active:bg-primaryPink active:text-black">
    {{ text }}</RouterLink
  >
</template>

<script setup>
defineProps({
  text: String,
  link: String,
});
</script>
