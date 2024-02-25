<script setup>

</script>

<template>
  <nav class="main-navbar">
    <div class="container">
      <div class="navbar-left">
        <img src="../assets/logo.png" alt="лого">
      </div>
      <ul>
        <li>
          <router-link :to="`/${$i18n.locale}`">{{ $t('main_menu.home') }}</router-link>
        </li>
        <li>
          <router-link :to="`/${$i18n.locale}/books`">{{ $t('main_menu.books') }}</router-link>
        </li>
        <li>
          <router-link :to="`/${$i18n.locale}/authors`">{{ $t('main_menu.authors') }}</router-link>
        </li>
        <li>
          <router-link :to="`/${$i18n.locale}/contact`">{{ $t('main_menu.contact') }}</router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<style scoped lang="scss">
.main-navbar {
  display: flex;
  justify-content: center;
  width: 100%;
  height: 80px;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 12;
  background: var(--primary-color);

  .container {
    display: flex;
    align-items: center;
    width: 1280px;
    justify-content: space-between;
  }

  ul {
    list-style: none;
    display: flex;
    justify-content: space-around;
    padding: 0;
    gap: 30px;

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