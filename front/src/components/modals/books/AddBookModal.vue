<script setup>
import {onMounted, ref} from 'vue';
import {getFormData} from "../../../utils.js";
import axios from "axios";
import Popup from "../../Popup.vue";

const emit = defineEmits(['cancel', 'reloadData']);
const errorMessage = ref('');
const showError = ref(false);
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
const formRules = {
  title: [
    {required: true, message: 'Please enter the title', trigger: 'blur'},
  ]
};
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
      .catch(error => {
        console.log(error);
        showError.value = true;
        if (error.response.status !== 200) {
          errorMessage.value = error.message;
        }
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
      .get('/authors/all', {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          authors.value = res.data.data;
        }
      })
      .catch(error => {
        showError.value = true;
        if (error.response.status !== 200) {
          errorMessage.value = error.message;
        }
      })
};
const emitCancel = () => {
  emit('cancel');
};
const resetError = () => {
  showError.value = false;
  errorMessage.value = '';
};

onMounted(() => {
  getAuthors();
});
</script>

<template>
  <div
      class="modal-overlay"
      @click="emitCancel"
      v-loading="loading"
      :element-loading-text="$t('loading')"
      element-loading-background="rgba(0, 0, 0, 0.7)"
  >
    <el-form
        @click.stop
        class="modal"
        :model="book"
        v-loading="loading"
        label-width="auto"

        label-position="top"
        status-icon
    >
      <h1 class="modal-title">{{ $t('label.add_book') }}</h1>
      <el-form-item
          :label="`${$t('titles.table_titles.books.name')}`"
          prop="title"
      >
        <el-input v-model="book.title"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.author')}`"
          prop="author"
      >
        <el-select
            multiple
            clearable
            filterable
            v-model="book.author"
        >
          <el-option
              v-for="author in authors"
              :key="author.id"
              :label="author.initials"
              :value="author.id"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.description')}`"
          prop="description"
      >
        <el-input v-model="book.description"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.isbn')}`"
          prop="isbn"
      >
        <el-input type="number" v-model="book.isbn"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.pages')}`"
          prop="pages"
      >
        <el-input v-model="book.pages" type="number"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.cover_image')}`"
          prop="cover_image"
      >

      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.publisher')}`"
          prop="publisher"
      >
        <el-input v-model="book.publisher"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.genre')}`"
          prop=""
      >
        <el-input v-model="book.genre"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.language')}`"
          prop="language"
      >
        <el-input v-model="book.language"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.publish_date')}`"
          prop="publish_date"
      >
        <el-date-picker format="YYYY-MM-DD" type="date" v-model="book.published_at"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.file')}`"
          prop="file"
      >
        <el-input @change="handleFileChange" type="file"/>
      </el-form-item>

      <el-form-item>
        <el-button @click="addBook" type="primary">{{ $t('buttons.save') }}</el-button>
        <el-button @click="emitCancel">{{ $t('buttons.cancel') }}</el-button>
      </el-form-item>
    </el-form>
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
  width: 400px;
  padding: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
  overflow-y: auto;
  max-height: -webkit-fill-available;
  max-height: -moz-available;

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
}

.el-form--label-top .el-form-item {
  width: -webkit-fill-available;
  margin-bottom: 0;
}
</style>