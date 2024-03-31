<script setup>
import {onMounted, ref} from 'vue';
import {getFormData} from "../../../utils.js";
import axios from "axios";
import Popup from "../../Popup.vue";
import i18n from "../../../i18n.js";
import router from "../../../router/index.js";

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
  author_ids: '',
  pages: '',
  cover_image: '',
  file: ''
});
const loading = ref(false);
const authors = ref([]);
const formRules = {
  title: [
    {
      required: true,
      message: i18n.global.t('validation.please_enter_the_title'),
      trigger: 'blur'
    },
  ]
};
const languages = [
  {label: 'English', value: 'English'},
  {label: 'Spanish', value: 'Spanish'},
  {label: 'French', value: 'French'},
  {label: 'Русский', value: 'Русский'},
  {label: 'Тоҷикӣ', value: 'Тоҷикӣ'},
];
const genres = [
  {label: 'Бадеи', value: 'Бадеи'},
  {label: 'Биология', value: 'Биология'},
  {label: 'Биофизика', value: 'Биофизика'},
  {label: 'Гео-информатика', value: 'Гео-информатика'},
  {label: 'Гео-мир', value: 'Гео-мир'},
  {label: 'Гео-физика', value: 'Гео-физика'},
  {label: 'Демография', value: 'Демография'},
  {label: 'Естествознание', value: 'Естествознание'},
  {label: 'История', value: 'История'},
  {label: 'Медицина', value: 'Медицина'},
  {label: 'Педагогика и психология', value: 'Педагогика и психология'},
  {label: 'Политология и социология', value: 'Политология и социология'},
  {label: 'Психология', value: 'Психология'},
  {label: 'Учебная литература', value: 'Учебная литература'},
  {label: 'Философия', value: 'Философия'},
  {label: 'Химия', value: 'Химия'},
  {label: 'Художественная', value: 'Художественная'},
  {label: 'Языкознание', value: 'Языкознание'}
];

const handleFileChange = (e) => {
  book.value.file = e.target.files[0];
};
const handleImageChange = (e) => {
  book.value.cover_image = e.target.files[0];
};
const addBook = async () => {
  loading.value = true;
  const payload = getFormData(book.value);
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
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
        showError.value = true;
        if (err.response.status === 401) {
          sessionStorage.removeItem('token');
          sessionStorage.removeItem('user');
          router.push({name: 'login'});
        }
        if (err.response.status !== 200) {
          errorMessage.value = err.message;
        }
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
          authors.value = res.data;
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
        label-width="auto"
        label-position="top"
        status-icon
        :rules="formRules"
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
            v-model="book.author_ids"
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
          :label="`${$t('titles.table_titles.books.publisher')}`"
          prop="publisher"
      >
        <el-input v-model="book.publisher"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.cover_image')}`"
          prop="cover_image"
      >
        <input
            type="file"
            accept="image/*"
            @change="handleImageChange"
        />
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.file')}`"
          prop="file"
      >
        <input
            type="file"
            accept=".pdf"
            @change="handleFileChange"
        />
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.genre')}`"
          prop="genre"
      >
        <el-select
            :label="`${$t('titles.table_titles.books.genre')}`"
            v-model="book.genre"
            clearable
            filterable
        >
          <el-option
              v-for="(genre, i) in genres"
              :key="i"
              :label="genre.label"
              :value="genre.value"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.language')}`"
          prop="language"
      >
        <el-select
            :label="`${$t('titles.table_titles.books.language')}`"
            v-model="book.language"
            clearable
            filterable
        >
          <el-option
              v-for="(lang, i) in languages"
              :key="i"
              :label="lang.label"
              :value="lang.value"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.publish_date')}`"
          prop="publish_at"
      >
        <el-date-picker
            format="YYYY-MM-DD"
            type="date"
            v-model="book.published_at"
            :placeholder="`${$t('buttons.pick_a_date')}`"
        />
      </el-form-item>
      <el-form-item class="buttons-block">
        <el-button @click="addBook" type="primary">{{ $t('buttons.save') }}</el-button>
        <el-button @click="emitCancel">{{ $t('buttons.cancel') }}</el-button>
      </el-form-item>
    </el-form>
    <Popup v-if="showError" :message="errorMessage" @close="resetError"/>
  </div>
</template>

<style scoped lang="scss">
.el-form--label-top .el-form-item {
  width: -webkit-fill-available;
  width: -moz-available;
  margin-bottom: 0;
}

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

  .buttons-block {
    display: flex;
    justify-content: center;
    width: auto;
  }
}
</style>