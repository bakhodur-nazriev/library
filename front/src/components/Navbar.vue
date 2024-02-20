<script setup>

</script>

<template>
  <nav class="main_navbar">
    <div class="navbar-left">
      <img src="../assets/logo.png" alt="лого">
    </div>
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
  </nav>
</template>

<style scoped lang="scss">
.main_navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  background: rgba(0, 0, 0, 0.8);

  ul {
    list-style: none;
    display: flex;
    justify-content: space-around;
    padding: 0;
    gap: 10px;

    li {
      a {
        text-decoration: none;
        font-weight: 400;
        color: var(--white-color);
      }
    }
  }
}
</style>