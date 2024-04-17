<script setup>
import {ref} from 'vue';
import axios from "axios";
import router from "../../../router/index.js";

const emit = defineEmits(['close', 'reloadData']);
const props = defineProps(['userId']);
const loading = ref(false);

const emitCLose = () => {
  emit('close');
};
const deleteUser = async () => {
  loading.value = true;

  const authorToken = sessionStorage.getItem('token');
  const headers = {
    "Content-type": "application/json",
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
    "Authorization": `Bearer ${authorToken}`
  };

  await axios
      .delete('/admin/users/' + props.userId, {headers})
      .then(res => {
        if (res.status === 200) {
          emit('reloadData', true);
          emitCLose();
        }
      })
      .catch(err => {
        if (err.response.status === 401) {
          sessionStorage.removeItem('token');
          sessionStorage.removeItem('user');
          router.push({name: 'login'});
        }
        console.log(err);
      })
      .finally(() => loading.value = false);
};
</script>

<template>
  <div class="modal-overlay" @click="emitCLose">
    <div
        class="modal"
        @click.stop
        v-loading="loading"
        :element-loading-text="$t('loading')"
    >
      <div>
        <h3>{{ $t('notifications.remove_message') }}</h3>
        <ul class="modal-list">
          <li class="modal-list__item">
            <button @click="deleteUser">{{ $t('buttons.yes') }}</button>
          </li>
          <li class="modal-list__item">
            <button @click="emitCLose">{{ $t('buttons.no') }}</button>
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
    margin-bottom: 15px;
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