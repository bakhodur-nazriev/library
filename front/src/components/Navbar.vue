<script setup>
import {onMounted, onBeforeUnmount} from "vue";
import LocalesDropdown from "./LocalesDropdown.vue";
import {ArrowDown, UserFilled} from "@element-plus/icons-vue";
import axios from "axios";
import router from "../router/index.js";

const handleScroll = () => {
  const navbar = document.querySelector('.main-navbar');
  if (navbar) {
    const shouldHaveBackground = window.scrollY > 0;
    navbar.classList.toggle('has-background', shouldHaveBackground);
  }
};
const logout = async () => {
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .post('/logout', null, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          sessionStorage.removeItem('token');
          router.push({name: 'login'});
        }
      })
      .catch(err => {
        console.log(err);
      })
}

window.addEventListener('scroll', handleScroll);
onMounted(() => {
  handleScroll();
});
onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
  <nav class="main-navbar">
    <div class="container">
      <div class="navbar-left">
        <img src="../assets/main-logo-1.png" alt="лого">
      </div>
      <ul class="navbar-list">
        <li class="navbar-list_item">
          <router-link
              :to="`/${$i18n.locale}/`"
              :class="{ 'active-link': $route.path === `/${$i18n.locale}/` }"
          >
            {{ $t('main_menu.home') }}
          </router-link>
        </li>
        <li class="navbar-list_item">
          <router-link
              :to="`/${$i18n.locale}/books`"
              :class="{ 'active-link': $route.path === `/${$i18n.locale}/books` }"
          >
            {{ $t('main_menu.books') }}
          </router-link>
        </li>
        <li class="navbar-list_item">
          <router-link
              :to="`/${$i18n.locale}/authors`"
              :class="{ 'active-link': $route.path === `/${$i18n.locale}/authors` }"
          >
            {{ $t('main_menu.authors') }}
          </router-link>
        </li>
        <li class="navbar-list_item">
          <router-link
              :to="`/${$i18n.locale}/contact`"
              :class="{ 'active-link': $route.path === `/${$i18n.locale}/contact` }"
          >
            {{ $t('main_menu.contact') }}
          </router-link>
        </li>
        <li class="navbar-list_item">
          <LocalesDropdown/>
        </li>

        <li class="navbar-list_item">
          <el-button @click="logout">{{ $t('buttons.logout') }}</el-button>
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
  z-index: 16;
  transition: 0.3s;

  &.has-background {
    background: rgba(0, 0, 0, 0.8);
  }

  .container {
    display: flex;
    align-items: center;
    width: 1280px;
    justify-content: space-between;

    .navbar-left {
      display: flex;

      img {
        width: 210px;
      }
    }

    .navbar-list {
      list-style: none;
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 0;
      gap: 30px;

      &_item {
        position: relative;

        a {
          text-decoration: none;
          font-weight: 400;
          font-size: 14px;
          color: var(--color-white);

          &.active-link {
            color: var(--color-active-link);

            &::after {
              content: '';
              position: absolute;
              top: 20px;
              left: 0;
              width: 100%;
              height: 2px;
              background-color: var(--color-active-link); // Цвет линии
              border-radius: 4px; // Закругление углов под линией
            }
          }
        }
      }
    }
  }
}

.example-showcase .el-dropdown + .el-dropdown {
  margin-left: 15px;
}

.example-showcase .el-dropdown-link {
  cursor: pointer;
  color: var(--el-color-primary);
  display: flex;
  align-items: center;
}
</style>