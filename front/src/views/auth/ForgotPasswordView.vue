<script setup>

</script>

<template>
  <main class="forgot-password-block">
    <form action="" class="forgot-password__form">
      <h1>{{ $t('titles.forgot_password') }}</h1>
      <p>{{ $t('subtitles.we_will_send_password') }}</p>
      <input
          class="forgot-password__form-input"
          type="email" :placeholder="`${$t('placeholders.email')}`"
          required
      >
      <button type="button" class="forgot-password__form-button">{{ $t('buttons.send') }}</button>
    </form>
  </main>
</template>

<style scoped lang="scss">
.forgot-password {
  &-block {
    box-shadow: 0 6px 10px -4px rgba(0,0,0,.15);
    background-color: var(--color-white);
    border-radius: 10px;
    padding: 20px;
  }
  &__form {
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

    p {
      margin: 0;
      font-size: 18px;
      line-height: 1.3;
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
  }
}
</style>