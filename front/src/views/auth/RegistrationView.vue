<script setup>
import {ref} from "vue";
import EyeIcon from "../../components/icons/EyeIcon.vue";
import EyeSlashIcon from "../../components/icons/EyeSlashIcon.vue";
import {getFormData} from "../../utils.js";
import axios from "axios";
import router from "../../router/index.js";

let isPasswordVisible = ref(true);
const name = ref('');
const email = ref('');
const password = ref('');

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value
}

const register = async (e) => {
  e.preventDefault();
  const payload = getFormData({
    email: email.value,
    password: password.value,
    name: name.value
  });

  const headers = {
    'Content-Type': 'multipart/form-data',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
  }

  await axios
      .post('/register', payload, {headers})
      .then(res => {
        if (res.status === 200) {
          router.push({name: 'login'});
        }
      })
      .catch(err => {
        console.log(err)
      })
}
</script>

<template>
  <main class="login-block">
    <form class="login-form" @submit="register">
      <h1>{{ $t('titles.registration') }}</h1>
      <input
          required
          type="text"
          v-model="name"
          class="login-form-input"
          :placeholder="`${$t('placeholders.name')}`"
      >

      <input
          required
          type="email"
          v-model="email"
          class="login-form-input"
          :placeholder="`${$t('placeholders.email')}`"
      >

      <div class="input-with-eye">
        <input
            class="login-form-input" :type="isPasswordVisible ? 'password' : 'text'"
            :placeholder="`${$t('placeholders.password')}`"
            v-model="password"
            required
        >
        <button type="button" class="eye-button" @click="togglePasswordVisibility">
          <EyeSlashIcon v-if="isPasswordVisible"/>
          <EyeIcon v-else/>
        </button>
      </div>


      <button type="submit" class="login-form-button">
        {{ $t('buttons.registration') }}
      </button>
      <router-link
          :to="`/${$i18n.locale}/login`"
          class="link create-account"
      >
        {{ $t('label.have_account') }}
      </router-link>
    </form>
  </main>
</template>

<style scoped lang="scss">
.login {
  &-block {
    box-shadow: 0 6px 10px -4px rgba(0, 0, 0, .15);
    background-color: var(--color-white);
    border-radius: 10px;
    padding: 30px;
    max-width: 450px;
    width: 100%;
  }

  &-form {
    display: flex;
    gap: 20px;
    justify-content: center;
    align-items: center;
    flex-direction: column;

    h1 {
      font-size: 40px;
      margin: 0 0 10px;
      align-self: start;
      font-weight: 600;
      line-height: 1;
    }

    .forgot-password {
      align-self: start;
      font-size: 12px;
    }

    .create-account {
      font-size: 14px;
    }

    &-button {
      border: none;
      border-radius: 10px;
      background-color: var(--color-primary);
      color: var(--color-white);
      width: 100%;
      padding: 14px;
      font-size: 16px;
      margin-top: 25px;
      cursor: pointer;
      transition: all 0.15s ease-in-out;

      &:hover {
        opacity: 0.9;
      }
    }

    &-input {
      background-color: var(--bg-grey-color);
      border: none;
      padding: 14px;
      font-size: 14px;
      width: -webkit-fill-available;
      width: -moz-available;
      border-radius: 8px;
    }
  }
}

.input-with-eye {
  position: relative;
  display: flex;
  flex-direction: column;
  z-index: 1;
  width: -webkit-fill-available;
  width: -moz-available;
}


.eye-button {
  display: flex;
  position: absolute;
  background: none;
  border: none;
  cursor: pointer;
  right: 0;
  top: 50%;
  transform: translateY(-50%);

  svg {
    color: #b0b0b0;
  }
}
</style>