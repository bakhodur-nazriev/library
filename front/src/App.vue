<script setup>
import {computed} from "vue";
import AuthLayout from "./components/layouts/AuthLayout.vue";
import MainLayout from "./components/layouts/MainLayout.vue";
import {useRoute} from "vue-router";

const route = useRoute();
const layout = computed(() => {
  return route.meta.layout === "auth" ? AuthLayout : MainLayout;
});
</script>

<template>
  <component :is="layout">
    <router-view></router-view>
  </component>
</template>

<style scoped lang="scss">

</style>
