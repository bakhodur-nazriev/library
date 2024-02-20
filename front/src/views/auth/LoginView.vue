<script setup>

</script>

<template>
  <main class="login-block">
    <form action="" class="login-form">
      <h1>{{ $t('titles.login') }}</h1>
      <input class="login-form-input" type="email" :placeholder="`${$t('placeholders.email')}`" required>
      <input class="login-form-input" type="password" :placeholder="`${$t('placeholders.password')}`">

      <button type="button" class="login-form-button">{{ $t('buttons.login') }}</button>
      <router-link to="/registration" class="link">{{ $t('label.have_account') }}</router-link>
    </form>
  </main>
</template>

<style scoped lang="scss">
.login {
  &-block {
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

    &-button {
      border: none;
      border-radius: 10px;
      background-color: var(--primary-color);
      color: var(--white-color);
      width: 100%;
      padding: 14px;
      font-size: 16px;
      margin-top: 25px;
    }

    &-input {
      background-color: var(--bg-grey-color);
      border: none;
      padding: 14px;
      font-size: 14px;
      width: -webkit-fill-available;
      border-radius: 8px;
    }
  }
}
</style>