<script setup xmlns="http://www.w3.org/1999/html">

import Navbar from "../Navbar.vue";
import MainFooter from "../MainFooter.vue";
</script>

<template>
  <div class="main-block">
    <div class="navbar-block">
      <Navbar/>
    </div>
    <main>
      <slot></slot>
    </main>

    <MainFooter/>
  </div>
</template>

<style scoped lang="scss">
.main-block {
  display: flex;
  flex-direction: column;
  height: 100vh;

  .navbar-block {
    display: flex;
    justify-content: center;
  }
}
</style>