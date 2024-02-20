<script setup>

</script>

<template>
  <div class="container">
    <router-view></router-view>
  </div>
</template>

<style scoped lang="scss">
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  min-width: 100vw;
  background-color: var(--bg-grey-color);
}
</style>