<script setup>

import Navbar from "../Navbar.vue";
</script>

<template>
  <div class="container">
    <Navbar/>

    <main>
      <router-view/>
    </main>
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