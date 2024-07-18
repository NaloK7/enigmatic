<template>
    <a :href="link" >
        {{ text }}
    </a>
</template>
<script  setup>

defineProps({
  link: String,
  text: String,
})
</script>
<style  scoped>
a {
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


