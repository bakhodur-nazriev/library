<script setup>
import {ref} from "vue";
import axios from "axios";
import {getFormData} from "../../utils.js";
import EyeIcon from "../../components/icons/EyeIcon.vue";
import EyeSlashIcon from "../../components/icons/EyeSlashIcon.vue";
import Popup from "../../components/Popup.vue";
import router from "../../router/index.js";

let isPasswordVisible = ref(true);
const email = ref('');
const password = ref('');
const errorMessage = ref('');
const showError = ref(false);
const loading = ref(false);
const rules = ref([]);

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value
}
const login = async (e) => {
  e.preventDefault();
  const payload = getFormData({
    email: email.value,
    password: password.value
  });

  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*'
  };

  await axios
      .post('/login', payload, {headers})
      .then(res => {
        if (res.status === 200) {
          sessionStorage.setItem('token', res.data.token);
          router.push({name: 'home'});
        }
      })
      .catch(error => {
        showError.value = true;
        if (error.response.status !== 200) {
          errorMessage.value = error.response.data.error;
        }
      });
};
const resetError = () => {
  showError.value = false;
  errorMessage.value = '';
};
</script>

<template>
  <main class="login-block">
    <form class="login-form" @submit="login">
      <h1>{{ $t('titles.login') }}</h1>
      <input
          required
          type="email"
          v-model="email"
          class="login-form__input"
          :placeholder="`${$t('placeholders.email')}`"
      >

      <div class="input-with-eye">
        <input
            required
            v-model="password"
            class="login-form__input password-input"
            :type="isPasswordVisible ? 'password' : 'text'"
            :placeholder="`${$t('placeholders.password')}`"
        >
        <button type="button" class="eye-button" @click="togglePasswordVisibility">
          <EyeSlashIcon v-if="isPasswordVisible"/>
          <EyeIcon v-else/>
        </button>
      </div>
      <router-link
          :to="`/${$i18n.locale}/forgot-password`"
          class="link forgot-password"
      >
        {{ $t('label.forgot_password') }}
      </router-link>


      <button
          type="submit"
          class="login-form__button"
      >
        {{ $t('buttons.login') }}
      </button>
      <router-link :to="`/${$i18n.locale}/register`" class="link create-account">
        {{ $t('label.create_account') }}
      </router-link>
    </form>
    <Popup v-if="showError" :message="errorMessage" @close="resetError"/>
  </main>
</template>

<style scoped lang="scss">
.login {
  &-block {
    box-shadow: 0 6px 10px -4px rgba(0, 0, 0, .15);
    background-color: var(--color-white);
    border-radius: 10px;
    padding: 30px;
  }

  &-form {
    display: flex;
    gap: 20px;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    width: 450px;

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

    &__button {
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

    &__input {
      background-color: var(--bg-grey-color);
      border: none;
      padding: 14px;
      font-size: 14px;
      width: -webkit-fill-available;
      border-radius: 8px;
    }

    .password-input {
      padding-right: 35px;
    }
  }
}

.input-with-eye {
  position: relative;
  display: flex;
  flex-direction: column;
  z-index: 1;
  width: inherit;
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