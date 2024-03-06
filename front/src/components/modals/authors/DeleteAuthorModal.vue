<script setup>
import {defineEmits, ref} from 'vue';
import axios from "axios";

const emit = defineEmits(['close', 'reloadData']);
const props = defineProps(['authorId']);
const loading = ref(false);
const emitClose = () => {
  emit('close');
}
const deleteAuthor = async () => {
  loading.value = true;
  const authorToken = sessionStorage.getItem('token');
  const headers = {
    "Content-type": "applications/json",
    "Authorization": `Bearer ${authorToken}`
  };

  await axios
      .delete('/admin/authors/' + props.authorId, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          emit('reloadData', true);
          emit('close');
        }
      })
      .catch(err => {
        console.log(err);
      })
      .finally(() => loading.value = false);
};
</script>

<template>
  <div class="modal-overlay" @click="emitClose" v-loading="loading"
       :element-loading-text="$t('loading')">
    <div class="modal" @click.stop>
      <div>
        <h3>{{ $t('notifications.remove_message') }}</h3>
        <ul class="modal-list">
          <li class="modal-list__item">
            <button @click="deleteAuthor">{{ $t('buttons.yes') }}</button>
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
  background-color: var(--color-white);
  border-radius: 10px;
  max-width: 400px;
  padding: 25px;
  display: flex;
  flex-direction: column;
  align-items: end;

  h3 {
    text-align: center;
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
        background-color: var(--color-primary);
        border-radius: 8px;
        padding: 8px 15px;
        color: var(--color-white);
        cursor: pointer;
      }
    }
  }
}
</style>