<script setup>
import {ref} from "vue";
import EyeIcon from "../../components/icons/EyeIcon.vue";
import EyeSlashIcon from "../../components/icons/EyeSlashIcon.vue";

let isPasswordVisible = ref(true);

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value
}
</script>

<template>
  <main class="login-block">
    <form action="" class="login-form">
      <h1>{{ $t('titles.login') }}</h1>
      <input
          class="login-form__input"
          type="email"
          :placeholder="`${$t('placeholders.email')}`"
          required
      >

      <div class="input-with-eye">
        <input
            class="login-form__input"
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


      <button type="button" class="login-form__button">{{ $t('buttons.login') }}</button>
      <router-link :to="`/${$i18n.locale}/registration`" class="link create-account">
        {{ $t('label.create_account') }}
      </router-link>
    </form>
  </main>
</template>

<style scoped lang="scss">
.login {
  &-block {
    box-shadow: 0 6px 10px -4px rgba(0,0,0,.15);
    background-color: var(--white-color);
    border-radius: 10px;
    padding: 20px;
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
      margin: 10px 0;
      align-self: start;
      font-weight: 600;
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
      background-color: var(--primary-color);
      color: var(--white-color);
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