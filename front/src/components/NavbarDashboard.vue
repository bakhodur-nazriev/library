<script setup>
import MenuIcon from "./icons/MenuIcon.vue";
import SearchInput from "./SearchInput.vue";
</script>

<template>
  <div>
    <nav class="sidebar">
      <ul class="list">
        <li class="list-item">
          <router-link :to="`/${$i18n.locale}/dashboard`">
            <img src="../assets/logo.png" alt="Логотип">
          </router-link>
        </li>
        <li class="list-item">
          <router-link :to="`/${$i18n.locale}/dashboard/authors`">
            {{ $t('label.authors') }}
          </router-link>
        </li>
        <li class="list-item">
          <router-link :to="`/${$i18n.locale}/dashboard/books`">
            {{ $t('label.books') }}
          </router-link>
        </li>
        <li class="list-item">
          <router-link :to="`/${$i18n.locale}/dashboard/users`">
            {{ $t('label.users') }}
          </router-link>
        </li>
      </ul>
    </nav>

    <nav class="navbar">
      <button class="toggle-menu">
        <MenuIcon/>
      </button>

      <SearchInput/>

      <div class="user-block">
        <router-link class="link" :to="`/${$i18n.locale}/user`">Bakhodur</router-link>
        <img src="../assets/users/img.png" alt="">
      </div>
    </nav>
  </div>
</template>

<style scoped lang="scss">
.sidebar {
  background-color: var(--primary-color);
  height: 100vh;
  width: 240px;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 10;

  .list {
    list-style: none;
    padding: 15px;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 15px;

    &-item {
      a {
        transition: all 0.1s ease-in-out;
        text-decoration: none;
        color: var(--white-color);
        font-size: 14px;
        font-weight: 500;

        &:hover {
          opacity: 0.5;
        }
      }
    }
  }
}

.navbar {
  position: fixed;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: var(--white-color);
  height: 70px;
  padding: 0 15px 0 260px;
  width: -webkit-fill-available;
  z-index: 9;
  box-shadow: 0 0 3px 0 rgba(0, 0, 0, .07), 0 2px 2px 0 rgba(0, 0, 0, .04);

  .toggle-menu {
    border: none;
    background: none;
    cursor: pointer;
    display: flex;
    height: fit-content;
    padding: 0;
  }

  .user-block {
    display: flex;
    align-items: center;
    gap: 10px;

    img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      cursor: pointer;
    }
  }
}
</style>