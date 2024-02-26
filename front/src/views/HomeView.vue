<script setup>
import SampleTab from "../components/SampleTab.vue";
import {ref} from "vue";

const books = ref([
  {
    "name": "Just Test!",
    "author": "Odam",
  }
]);
</script>

<template>
  <div class="container">
    <section class="header">
      <img src="../assets/kazuend-DHk_mju83z0-unsplash.jpg" alt="">
      <SampleTab/>
    </section>

    <section class="">
      <ul>
        <li v-for="(book, i) in books" :key="i">
          {{ book }}
        </li>
      </ul>
    </section>
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
  
  .header {
    position: relative;
  }
}
</style>
