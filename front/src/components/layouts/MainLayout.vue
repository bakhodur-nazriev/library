<script setup>

import Navbar from "../Navbar.vue";
import MainFooter from "../MainFooter.vue";
</script>

<template>
  <div class="container">
    <Navbar/>

    <main>
      <slot></slot>
    </main>

    <MainFooter/>
  </div>
</template>

<style scoped lang="scss">
.container {
  height: 100vh;
  width: 100vw;
  box-sizing: border-box;
  position: relative;
}
</style>