<script setup>
import {ref} from 'vue';
import {getFormData} from "../../../utils.js";
import axios from "axios";
import router from "../../../router/index.js";
import Popup from "../../Popup.vue";

const emit = defineEmits(['cancel', 'reloadData']);
const user = ref({
  name: '',
  email: '',
  password: ''
});
const errorMessage = ref('');
const showError = ref(false);
const loading = ref(false);
const addUser = async () => {
  loading.value = true;
  const payload = getFormData(user.value);

  const authToken = sessionStorage.getItem('token');
  const headers = {
    "Authorization": `Bearer ${authToken}`,
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
  };

  await axios
      .post('/admin/users', payload, {headers})
      .then(res => {
        loading.value = false;
        if (res.status === 200 || res.status === 201) {
          emit('reloadData', true);
          emitCancel();
        }
      })
      .catch(err => {
        if (err.response.status === 401) {
          sessionStorage.removeItem('token');
          sessionStorage.removeItem('user');
          router.push({name: 'login'});
        }
        console.log(err);
        if (err.response.status !== 200) {
          errorMessage.value = err.message;
        }
      })
      .finally(() => loading.value = false);
};
const emitCancel = () => {
  emit('cancel');
};
const resetError = () => {
  showError.value = false;
  errorMessage.value = '';
};
</script>

<template>
  <div class="modal-overlay"
       @click="emitCancel"
       v-loading="loading"
       :element-loading-text="$t('loading')"
       element-loading-background="rgba(0, 0, 0, 0.7)"
  >
    <div class="modal" @click.stop>
      <h1 class="modal-title">{{ $t('label.add_user') }}</h1>
      <ul class="input-list">
        <li class="input-list__item">
          <input
              type="text"
              v-model="user.name"
              :placeholder="`${$t('titles.table_titles.users.name')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              type="text"
              v-model="user.email"
              :placeholder="`${$t('titles.table_titles.users.email')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              type="password"
              v-model="user.password"
              :placeholder="`${$t('titles.table_titles.users.password')}`"
          />
        </li>
      </ul>
      <ul class="button-list">
        <li class="button-list__item">
          <button @click="addUser">{{ $t('buttons.save') }}</button>
        </li>
        <li class="button-list__item">
          <button @click="emitCancel">{{ $t('buttons.cancel') }}</button>
        </li>
      </ul>
    </div>
    <Popup v-if="showError" :message="errorMessage" @close="resetError"/>
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
  border-radius: 12px;
  padding: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;

  &-title {
    font-size: 26px;
    margin: 0;
  }

  .input-list,
  .button-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .input-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: -webkit-fill-available;
    width: -moz-available;

    &__item {
      input {
        background-color: var(--color-white);
        border-radius: 8px;
        border: 1px solid var(--color-gray);
        box-sizing: border-box;
        color: var(--color-gray);
        font-size: 14px;
        outline: none;
        padding: 12px 15px;
        transition: border-color .2s cubic-bezier(.645, .045, .355, 1);
        width: 100%;
      }
    }
  }

  .button-list {
    display: flex;
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