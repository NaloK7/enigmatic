<template>
  <RouterLink :to="link"> {{ text }}</RouterLink>
</template>
<script setup>
defineProps({
  link: String,
  text: String,
});
</script>
<style scoped>
a {
  display: block;
  margin: 17px;
  height: 28px;
  width: 92px;
  border: 2px solid #17ae9f;
  border-radius: 5px;
  background: #18544e;
  color: white;
  font-family: "Audiowide";
  text-align: center;
  line-height: 1.4em;
}
a:hover {
  border: 2px solid #18544e;
  background: #17ae9f;
  color: black;
}
</style>
