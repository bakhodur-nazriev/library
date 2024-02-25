<script setup>
import {defineEmits} from 'vue';

const emit = defineEmits(['close']);

const emitClose = () => {
  emit('close');
}
</script>

<template>
  <div class="modal-overlay" @click="emitClose">
    <div class="modal" @click.stop>
      <div>
        <h3>{{ $t('notifications.remove_message') }}</h3>
        <ul class="modal-list">
          <li class="modal-list__item">
            <button>{{ $t('buttons.yes') }}</button>
          </li>
          <li class="modal-list__item">
            <button @click="emitClose">{{ $t('buttons.no') }}</button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 15;
}

.modal {
  background-color: var(--white-color);
  border-radius: 10px;
  max-width: 400px;
  padding: 25px;
  display: flex;
  flex-direction: column;
  align-items: end;

  h3 {
    margin-top: 0;
  }

  &-list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    justify-content: center;
    gap: 15px;

    &__item {
      button {
        font-size: 16px;
        border: none;
        background-color: var(--primary-color);
        border-radius: 8px;
        padding: 8px 15px;
        color: var(--white-color);
        cursor: pointer;
      }
    }
  }
}

//.close-button {
//  background: none;
//  border: none;
//  cursor: pointer;
//  display: flex;
//  padding: 0;
//  margin-bottom: 5px;
//
//  svg {
//    width: 24px;
//    height: 24px;
//    color: var(--primary-color);
//  }
//}
</style>