<script setup>
import {ref} from 'vue';
import {getFormData} from "../../../utils.js";
import axios from "axios";

const emit = defineEmits(['close', 'reloadData']);
const props = defineProps(['selectedUser']);

const user = ref({
  name: props.selectedUser.name,
  email: props.selectedUser.email,
  password: ''
});

const editData = async () => {
  const payload = getFormData(user.value);
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .patch('/admin/users/' + props.selectedUser.id, payload, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          emit('reloadData', true);
          emitClose();
        }
      })
      .catch(err => {
        console.log(err);
      })
};
const emitClose = () => {
  emit('close');
}
</script>

<template>
  <div class="modal-overlay" @click="emitClose">
    <div class="modal" @click.stop>
      <h1 class="modal-title">{{ $t('label.edit_user') }}</h1>
      <ul class="input-list">
        <li class="input-list__item">
          <input
              v-model="user.initials"
              type="text"
              :placeholder="`${$t('titles.table_titles.users.name')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              v-model="user.email"
              type="text"
              :placeholder="`${$t('titles.table_titles.users.email')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              v-model="user.password"
              type="password"
              :placeholder="`${$t('titles.table_titles.users.password')}`"
          />
        </li>
      </ul>
      <ul class="button-list">
        <li class="button-list__item">
          <button @click="editData">{{ $t('buttons.save') }}</button>
        </li>
        <li class="button-list__item">
          <button @click="emitClose">{{ $t('buttons.cancel') }}</button>
        </li>
      </ul>
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
  border-radius: 12px;
  width: 400px;
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
    width: inherit;

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