<script setup>
import NavbarDashboard from "../NavbarDashboard.vue";
</script>

<template>
  <div class="main-dashboard__block">
    <NavbarDashboard/>

    <div class="container">
      <slot></slot>
    </div>
  </div>
</template>

<style scoped lang="scss">
.main-dashboard__block {
  display: flex;
}

.container {
  background-color: var(--bg-grey-color);
  width: -webkit-fill-available;
  width: -moz-available;
  height: -webkit-fill-available;
  height: -moz-available;
  padding-top: 70px;
  padding-left: 240px;
  position: absolute;
}
</style>