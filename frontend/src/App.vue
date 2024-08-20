<template>
  <headerComp ref="headerRef"></headerComp>
  <RouterView ref="pageRef" :key="$route.fullPath" />
  <footerComp></footerComp>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { RouterView } from "vue-router";
import headerComp from "@/components/header.vue";
import footerComp from "@/components/footer.vue";

const headerRef = ref(null);
const pageRef = ref(null);

onMounted(() => {
  if (headerRef.value) {
    const headerHeight = headerRef.value.$el.offsetHeight;
    const pageHeight = pageRef.value.$el.offsetHeight;
    console.log("Header height:", headerHeight + pageHeight);
    // You can now use the headerHeight variable as needed
  }
});
</script>
