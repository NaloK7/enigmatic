
<template>
    <a
      :href="link"
      :class="[width]"
      class="flex justify-center items-center font-audiowide mx-4 w-44 rounded-xl h-10 bg-darkPink border-2 border-primaryPink hover:border-darkPink hover:bg-primaryPink tracking-wide text-lg hover:text-black"
      
      >{{text}}
    </a>
</template>

<script setup>
defineProps({
    text: String,
    link: String,
    width: "w-44",
})
</script>

<style  scoped>
a {

}
</style>