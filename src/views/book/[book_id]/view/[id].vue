<template>
  <h1>Livre {{ bookId }}</h1>
</template>
<script setup>
import { useRoute } from "vue-router";
const route = useRoute();
const bookId = route.params.book_id;
</script>
