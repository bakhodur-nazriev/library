<script setup>
import {onMounted, ref, watch} from 'vue';
import {getFormData} from "../../../utils.js";
import axios from "axios";
import Popup from "../../Popup.vue";
import router from "../../../router/index.js";

const emit = defineEmits(['close']);
const props = defineProps(['selectedBook']);
const book = ref({
  title: props.selectedBook.title,
  // incoming link is just path not pdf file
  file: '',
  authors: props.selectedBook.authors,
  description: props.selectedBook.description,
  ISBN: props.selectedBook.isbn,
  publisher: props.selectedBook.publisher,
  genre: props.selectedBook.genre,
  language: props.selectedBook.language,
  pages: props.selectedBook.pages,
  published_at: props.selectedBook.published_at,
  cover_image: props.selectedBook.cover_image
});
const loading = ref(false);
const authors = ref([]);
const formRules = {
  title: [
    {required: true, message: 'Please enter the title', trigger: 'blur'},
  ]
};
const formRef = ref(null);
const showError = ref(false);
const errorMessage = ref('');
const selectedAuthors = ref(book.value.authors);

watch(selectedAuthors, (newValue, oldValue) => {
  console.log(newValue, oldValue);
});

const handleFileChange = (e) => {
  book.value.file = e.target.files[0];
};
const handleImageChange = (e) => {
  book.value.cover_image = e.target.files[0];
};
const editData = async () => {
  loading.value = true;
  const payload = getFormData(book.value);

  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Authorization': `Bearer ${authToken}`,
    'Content-Type': 'multipart/form-data',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
  };
  const isValid = await formRef.value.validate();

  if (!isValid) {
    return;
  }

  await axios
      .post('/admin/books/' + props.selectedBook.id, payload, {headers})
      .then(res => {
        loading.value = false;
        if (res.status === 200 || res.status === 201) {
          emit('reloadData', true);
          emitClose();
        }
      })
      .catch(err => {
        if (err.response.status === 401) {
          router.push({name: 'login'});
          sessionStorage.removeItem('token');
          sessionStorage.removeItem('user');
        }
        console.log(err);
        showError.value = true;
        errorMessage.value = err.message;
        loading.value = false;
      })
      .finally(() => loading.value = false);
};
const getAuthors = async () => {
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Methods': '*',
    'Access-Control-Allow-Origin': '*',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get('/authors/all', {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          authors.value = Array.isArray(res.data) ? res.data : [];
        }
      })
      .catch(err => {
        if (err.response.status === 401) {
          router.push({name: 'login'});
          sessionStorage.removeItem('token');
          sessionStorage.removeItem('user');
        }
        console.log(err);
      })
};
const emitClose = () => {
  emit('close');
};

onMounted(() => {
  getAuthors();
});
</script>

<template>
  <div
      @click="emitClose"
      v-loading="loading"
      class="modal-overlay"
      :element-loading-text="$t('loading')"
      element-loading-background="rgba(0, 0, 0, 0.7)"
  >
    <el-form
        status-icon
        @click.stop
        ref="formRef"
        class="modal"
        style="max-width: 600px"
        :model="book"
        :rules="formRules"
        label-width="auto"
        label-position="top"
    >
      <h1 class="modal-title">{{ $t('label.edit_book') }}</h1>
      <el-form-item
          :label="`${$t('titles.table_titles.books.name')}`"
          prop="title"
      >
        <el-input v-model="book.title"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.author')}`"
          prop="authors"
      >
        <el-select
            multiple
            clearable
            filterable
            v-model="selectedAuthors"
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
          prop="ISBN"
      >
        <el-input v-model="book.ISBN"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.pages')}`"
          prop="pages"
      >
        <el-input v-model="book.pages"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.publisher')}`"
          prop="publisher"
      >
        <el-input v-model="book.publisher"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.genre')}`"
          prop="genre"
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
          :label="`${$t('titles.table_titles.books.cover_image')}`"
      >
        <input
            type="file"
            accept="image/*"
            @change="handleImageChange"
        />
      </el-form-item>
      <el-form-item :label="`${$t('titles.table_titles.books.file')}`">
        <input
            type="file"
            accept=".pdf"
            @change="handleFileChange"
        />
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.publish_date')}`"
          prop="published_at"
      >
        <el-date-picker format="YYYY-MM-DD" v-model="book.published_at" type="date"/>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" :disabled="loading.value" @click="editData">{{ $t('buttons.save') }}</el-button>
        <el-button @click="emitClose">{{ $t('buttons.cancel') }}</el-button>
      </el-form-item>
    </el-form>
    <Popup v-if="showError" :message="errorMessage"/>
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
  gap: 10px;
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
}

.el-form--label-top .el-form-item {
  width: -webkit-fill-available;
  width: -moz-available;
  margin-bottom: 0;
}
</style>