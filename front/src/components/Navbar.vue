<script setup>
import {onMounted, onBeforeUnmount, ref, computed} from "vue";
import LocalesDropdown from "./LocalesDropdown.vue";
import {UserFilled} from "@element-plus/icons-vue";
import axios from "axios";
import router from "../router/index.js";
import MainSearchTabs from "./MainSearchTabs.vue";
import i18n from "../i18n.js";

const userString = sessionStorage.getItem('user');
const user = JSON.parse(userString);
const show = ref(true);
const isShowModal = ref(false);
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
          sessionStorage.removeItem('user');
          router.push({name: 'login'});
        }
      })
      .catch(err => {
        if (err.response.status === 401) {
          router.push({name: 'login'});
          sessionStorage.removeItem('token');
          sessionStorage.removeItem('user');
        }
        console.log(err);
      })
};
const showNav = () => {
  show.value = !show.value;
};
const showModal = () => {
  isShowModal.value = !isShowModal.value;
};
const handleResize = () => {
  if (window.innerWidth > 991) {
    show.value = true;
  }
};

window.addEventListener('scroll', handleScroll);

onMounted(() => {
  handleScroll();
  window.addEventListener('resize', handleResize);
});
onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll);
  window.removeEventListener('resize', handleResize);
});
</script>

<template>
  <div class="container">
    <!--  Home header  -->
<!--    <div v-if="`${$route.path}` === `/${i18n.global.locale}/`">-->
<!--      <section class="sub-header">-->
<!--        <img src="../assets/slide4.jpg" alt="">-->
<!--        <MainSearchTabs/>-->
<!--      </section>-->
<!--    </div>-->
    <header>
      <div class="header-top_side">
        <div class="logo">
          <img src="../assets/main-logo-1.png" alt="лого">
        </div>
        <div class="right-block">
          <div @click="showNav" class="toggle-btn">
            <span></span>
            <span></span>
            <span></span>
          </div>
          <div class="user-profile_block">
            <div class="avatar-block" @click="showModal">
              <el-avatar :icon="UserFilled"/>
            </div>
            <div v-if="isShowModal" class="profile-modal_block">
              <span>{{ user.name }}</span>
              <span @click="logout">{{ $t('buttons.logout') }}</span>
            </div>
          </div>
        </div>
      </div>
      <nav v-show="show">
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
          <li class="navbar-list_item user">
            <div @click="showNav" class="toggle-btn">
              <span></span>
              <span></span>
              <span></span>
            </div>
            <div class="user-profile_block">
              <div class="avatar-block" @click="showModal">
                <el-avatar :icon="UserFilled"/>
              </div>
              <div v-if="isShowModal" class="profile-modal_block">
                <span>{{ user.name }}</span>
                <span @click="logout">{{ $t('buttons.logout') }}</span>
              </div>
            </div>
          </li>
        </ul>
      </nav>
    </header>

    <!--  Books header  -->
    <div v-if="`${$route.path}` === `/${i18n.global.locale}/books`">
      <section class="books-header">
        <img src="../assets/slide4.jpg" alt="">
        <div class="title-bar">
          <ul>
            <li></li>
            <li></li>
          </ul>
        </div>
        <section class="main-title_section">
          <h1>{{ $t('titles.banner.books') }}</h1>
        </section>
      </section>
    </div>

    <!--  Authors header  -->
  </div>
</template>

<style scoped lang="scss">
.container {
  width: 100%;
  max-width: 1280px;
  margin: 0 auto;
  z-index: 16;
  height: 400px;

  header {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
    z-index: 15;
    width: 100%;

    .logo {
      img {
        width: 200px;
      }
    }

    nav {
      display: flex;

      .navbar-list {
        list-style: none;
        display: flex;
        align-items: center;
        padding: 0;
        gap: 20px;

        &_item {
          position: relative;

          a {
            text-decoration: none;
            font-weight: 400;
            font-size: 14px;
            color: var(--color-white);
            cursor: pointer;

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
                border-radius: 4px;
              }
            }
          }
        }
      }
    }

    .right-block {
      position: relative;
      display: none;
      align-items: center;

      .toggle-btn {
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        width: 45px;
        border-radius: 4px;
        gap: 10px;
        padding: 5px;

        span {
          display: flex;
          height: 2px;
          width: 100%;
          background-color: #fff;
        }
      }
    }
  }

  //.sub-header {
  //  width: 100%;
  //
  //  img {
  //    position: absolute;
  //    top: 0;
  //    left: 0;
  //    width: 100%;
  //    height: 400px;
  //    filter: brightness(0.4);
  //  }
  //}

  .books-header {
    width: 100%;
    display: flex;
    justify-content: center;

    img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 400px;
      filter: brightness(0.4);
    }
  }

  .main-title_section {
    position: absolute;
    left: 0;
    top: 20%;
    width: 100%;
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);

    h1 {
      margin: 0;
      font-size: 42px;
      font-weight: bold;
      color: var(--color-white);
    }
  }

  .title-bar {
    display: flex;
    justify-content: center;
    position: absolute;
    background-color: var(--color-white);
    height: 2px;
    max-width: 400px;
    width: 100%;
    color: var(--color-white);
    top: 26%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);

    ul {
      position: absolute;
      padding: 0;
      margin: 0;
      list-style: none;
      display: flex;
      justify-content: center;
      gap: 12px;
      left: 0;
      top: 50%;
      -webkit-transform: translateY(-50%);
      transform: translateY(-50%);
      width: 100%;

      li {
        width: 12px;
        height: 12px;
        background-color: var(--color-white);
        transform: rotate(45deg)
      }
    }
  }
}

.profile-modal_block {
  display: flex;
  flex-direction: column;
  background-color: var(--color-white);
  position: absolute;
  top: 50px;
  right: 0;
  border-radius: 5px;
  padding: 10px 15px;
  gap: 8px;
  font-size: 14px;

  span {
    cursor: pointer;
  }
}

.avatar-block {
  user-select: none;
  cursor: pointer;
  padding: 4px;
  background-color: #fff;
  border-radius: 50%;
}

@media (max-width: 991px) {
  .container {
    header {
      background: rgba(0, 0, 0, 0.8);
      flex-direction: column;
      align-items: start;
      padding: 25px 25px;
      gap: 20px;

      .header-top_side {
        display: flex;
        justify-content: space-between;
        width: 100%;

        .right-block {
          display: flex;
          gap: 10px;

          .toggle-btn {
            display: flex;
            right: 25px;
            cursor: pointer;
          }
        }
      }

      nav {
        display: flex;
        justify-content: center;
        width: inherit;

        .navbar-list {
          flex-direction: column;

          .user {
            display: none;
          }
        }
      }
    }
    .main-title_section {
      h1 {
        font-size: 32px;
        text-align: center;
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