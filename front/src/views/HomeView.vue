<script setup>

import SampleTab from "../components/SampleTab.vue";
</script>

<template>
  <div class="container">
    <img src="../assets/kazuend-DHk_mju83z0-unsplash.jpg" alt="">
    <SampleTab/>
  </div>
</template>

<style scoped>
.container {
  position: relative;
  display: flex;
  justify-content: center;

  img {
    width: 100vw;
    height: auto;
    position: fixed;
    left: 0;
    filter: brightness(0.5);
  }
}
</style>
