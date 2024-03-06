<script setup>
import {defineEmits, onMounted, ref} from 'vue';
import {getFormData} from "../../../utils.js";
import axios from "axios";

const emit = defineEmits(['cancel', 'reloadData']);
const book = ref({
  title: '',
  isbn: '',
  description: '',
  publish_date: '',
  genre: '',
  language: '',
  publisher: '',
  author: '',
  pages: '',
  cover_image: '',
  file: ''
});
const loading = ref(false);
const authors = ref([]);
const handleFileChange = (e) => {
  book.value.file = e.target.files[0];
};
const addBook = async () => {
  loading.value = true;
  const payload = getFormData(book.value);

  const authToken = sessionStorage.getItem('token');
  const headers = {
    "Authorization": `Bearer ${authToken}`
  };

  await axios
      .post('/admin/books', payload, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          emit('reloadData', true);
          emitCancel();
        }
      })
      .catch(err => {
        console.log(err);
      })
      .finally(() => loading.value = false);
};
const getAuthors = async () => {
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Methods': '*',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get('/authors?per_page=15&page=1', {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          authors.value = res.data.data;
        }
      })
      .catch(err => {
        console.log(err);
      })
};
const emitCancel = () => {
  emit('cancel');
};

onMounted(() => {
  getAuthors();
});
</script>

<template>
  <div class="modal-overlay" @click="emitCancel">
    <div class="modal" @click.stop v-loading="loading" :element-loading-text="$t('loading')">
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
          <el-select
              v-model="book.author"
              multiple
              :placeholder="`${$t('titles.table_titles.books.author')}`"
          >
            <el-option
                v-for="author in authors"
                :key="author.id"
                :label="author.initials"
                :value="author.id"
            ></el-option>
          </el-select>
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
              type="text"
              v-model="book.cover_image"
              :placeholder="`${$t('titles.table_titles.books.cover_image')}`"
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
              v-model="book.published_at"
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
          <button @click="addBook">{{ $t('buttons.save') }}</button>
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