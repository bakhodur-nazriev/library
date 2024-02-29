<script setup>
import {defineEmits, ref} from 'vue';
import {getFormData} from "../../utils.js";
import axios from "axios";

const emit = defineEmits(['cancel']);
const author = ref({
  initials: '',
  nationality: '',
  biography: '',
  date_of_birth: '',
  photo_link: ''
});
const handleFileChange = (e) => {
  author.value.photo_link = e.target.files[0];
};
const addAuthor = async () => {
  // Update the author object with the formatted date_of_birth
  author.value.date_of_birth = new Date(author.value.date_of_birth)
      .toLocaleDateString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
      })
      .replace(/\//g, '-');

  const payload = getFormData(author.value);
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .post('/admin/authors', payload, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          emitCancel();
        }
      })
      .catch(err => {
        console.log(err);
      })
};
const emitCancel = () => {
  emit('cancel');
}
</script>

<template>
  <div class="modal-overlay" @click="emitCancel">
    <div class="modal" @click.stop>
      <ul class="input-list">
        <li class="input-list__item">
          <input
              v-model="author.initials"
              type="text"
              :placeholder="`${$t('titles.table_titles.authors.name')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              v-model="author.nationality"
              type="text"
              :placeholder="`${$t('titles.table_titles.authors.nationality')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              v-model="author.biography"
              type="text"
              :placeholder="`${$t('titles.table_titles.authors.biography')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              v-model="author.date_of_birth"
              type="date"
              :placeholder="`${$t('titles.table_titles.authors.date_of_birth')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              @change="handleFileChange"
              type="file"
              :placeholder="`${$t('titles.table_titles.authors.photo_link')}`"
          />
        </li>
      </ul>
      <ul class="button-list">
        <li class="button-list__item">
          <button @click="addAuthor">{{ $t('buttons.save') }}</button>
        </li>
        <li class="button-list__item">
          <button @click="emitCancel">{{ $t('buttons.cancel') }}</button>
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
  background-color: var(--white-color);
  border-radius: 12px;
  width: 400px;
  padding: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;

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
        background-color: var(--white-color);
        border-radius: 8px;
        border: 1px solid var(--secondary-color);
        box-sizing: border-box;
        color: var(--secondary-color);
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
        background-color: var(--primary-color);
        border-radius: 8px;
        padding: 8px 15px;
        color: var(--white-color);
        cursor: pointer;
      }
    }
  }
}
</style>