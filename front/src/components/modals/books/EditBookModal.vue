<script setup>
import {defineEmits} from 'vue';
import {getFormData} from "../../../utils.js";
import axios from "axios";

const emit = defineEmits(['close']);
const props = defineProps(['book']);
// const handleFileChange = (event) => {
//   book.value.file = event.target.files[0];
// };
const save = async () => {
  // const payload = getFormData(book.value);

  const authToken = sessionStorage.getItem('token');
  const headers = {
    "Authorization": `Bearer ${authToken}`
  };

  await axios
      .patch('/admin/books/' + props.book.id, payload, {headers})
      .then(res => {
        if (res.status === 200) {
          emitCancel();
        }
        console.log(res);
      })
      .catch(err => {
        console.log(err);
      })
};
const emitClose = () => {
  emit('close');
};
</script>

<template>
  <div class="modal-overlay" @click="emitClose">
    <div class="modal" @click.stop>
      <h1 class="modal-title">{{ $t('label.add_book') }}</h1>
      <ul class="input-list">
        <li class="input-list__item">
          <input
              type="text"
              v-model="book.title"
              :placeholder="`${$t('titles.table_titles.books.name')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              type="text"
              v-model="book.author"
              :placeholder="`${$t('titles.table_titles.books.author')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              type="text"
              v-model="book.description"
              :placeholder="`${$t('titles.table_titles.books.description')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              type="number"
              v-model="book.isbn"
              :placeholder="`${$t('titles.table_titles.books.isbn')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              type="number"
              v-model="book.pages"
              :placeholder="`${$t('titles.table_titles.books.pages')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              v-model="book.publisher"
              type="text"
              :placeholder="`${$t('titles.table_titles.books.publisher')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              type="text"
              v-model="book.genre"
              :placeholder="`${$t('titles.table_titles.books.genre')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              type="text"
              v-model="book.language"
              :placeholder="`${$t('titles.table_titles.books.language')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              type="date"
              v-model="book.publish_date"
              :placeholder="`${$t('titles.table_titles.books.publish_date')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              type="file"
              accept=".pdf"
              @change="handleFileChange"
              :placeholder="`${$t('titles.table_titles.books.file')}`"
          />
        </li>
      </ul>
      <ul class="button-list">
        <li class="button-list__item">
          <button @click="save">{{ $t('buttons.save') }}</button>
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
  background-color: var(--white-color);
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