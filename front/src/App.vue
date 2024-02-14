<script setup>

// import HomeView from "./views/HomeView.vue";
</script>

<template>
  <ul>
    <li>
      <router-link to="/">{{ $t('main_menu.home') }}</router-link>
    </li>
    <li>
      <router-link to="/books">{{ $t('main_menu.books') }}</router-link>
    </li>
    <li>
      <router-link to="/authors">{{ $t('main_menu.authors') }}</router-link>
    </li>
    <li>
      <router-link to="/contact">{{ $t('main_menu.contact') }}</router-link>
    </li>
  </ul>

  <router-view/>
</template>

<style scoped>

</style>
