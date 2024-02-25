<script setup>
import {computed} from "vue";
import AuthLayout from "./components/layouts/AuthLayout.vue";
import MainLayout from "./components/layouts/MainLayout.vue";
import DashboardLayout from "./components/layouts/DashboardLayout.vue";
import {useRoute} from "vue-router";

const route = useRoute();
const layout = computed(() => {
  if (route.meta.layout === "auth") {
    return AuthLayout;
  } else if (route.meta.layout === "dashboard") {
    return DashboardLayout;
  } else {
    return MainLayout;
  }
});
</script>

<template>
  <component :is="layout"></component>
</template>

<style scoped lang="scss"></style>
