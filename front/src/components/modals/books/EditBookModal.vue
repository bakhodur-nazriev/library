<script setup>
import {onMounted, ref} from 'vue';
import {getFormData} from "../../../utils.js";
import axios from "axios";

const emit = defineEmits(['close']);
const props = defineProps(['selectedBook']);
const book = ref({
  title: props.selectedBook.title,
  link: props.selectedBook.link,
  author_ids: (Array.isArray(props.selectedBook.author_ids)
      ? [...props.selectedBook.author_ids]
      : props.selectedBook.author_ids
          ? props.selectedBook.author_ids.split(',').map(author => parseInt(author.trim(), 10))
          : []),
  description: props.selectedBook.description,
  ISBN: props.selectedBook.isbn,
  publisher: props.selectedBook.publisher,
  genre: props.selectedBook.genre,
  language: props.selectedBook.language,
  pages: props.selectedBook.pages,
  published_at: props.selectedBook.published_at,
  // cover_book: props.selectedBook.cover_image
});
const loading = ref(false);
const authors = ref([]);
const formRules = {
  title: [
    {required: true, message: 'Please enter the title', trigger: 'blur'},
  ],
  author_ids: [
    {required: true, message: 'Please select at least one author', trigger: 'change'},
  ],
  ISBN: [
    {required: true, message: 'Please enter the ISBN', trigger: 'blur'},
    {pattern: /^[0-9]*$/, message: 'ISBN must contain only numbers', trigger: 'blur'},
  ],
  pages: [
    {required: true, message: 'Please enter the number of pages', trigger: 'blur'},
    {pattern: /^[0-9]*$/, message: 'Pages must contain only numbers', trigger: 'blur'},
  ]
};
const formRef = ref(null);

const handleFileChange = (e) => {
  book.value.link = e.target.files[0];
};
const editData = async () => {
  const isValid = await formRef.value.validate();

  if (!isValid) {
    return;
  }

  loading.value = true;
  book.value.published_at = new Date(props.selectedBook.published_at).toLocaleDateString('en-CA');

  const payload = getFormData(book.value);
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .post('/admin/books/' + props.selectedBook.id, payload, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          emit('reloadData', true);
          emitClose();
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
          authors.value = Array.isArray(res.data.data) ? res.data.data : [];
        }
      })
      .catch(err => {
        console.log(err);
      })
};
const emitClose = () => {
  emit('close');
};

onMounted(() => {
  getAuthors();
  if (typeof props.selectedBook.author === 'string') {
    const authorsArray = props.selectedBook.author.split(',').map(author => author.trim());
    book.value.author = authorsArray
        .filter(author => author !== '')
        .map(author => isNaN(author) ? author : parseInt(author, 10));
  }
});
</script>

<template>
  <div
      class="modal-overlay"
      @click="emitClose"
      element-loading-background="rgba(0, 0, 0, 0.7)"
      :element-loading-text="$t('loading')"
  >
    <el-form
        ref="formRef"
        style="max-width: 600px"
        :model="book"
        :rules="formRules"
        status-icon
        label-width="auto"
        class="modal"
        label-position="top"
        @click.stop
    >
      <h1 class="modal-title">{{ $t('label.edit_book') }}</h1>
      <el-form-item :label="`${$t('titles.table_titles.books.name')}`" prop="title">
        <el-input v-model="book.title"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.books.author')}`"
          prop="author"
      >
        <el-select multiple v-model="book.author_ids" filterable clearable>
          <el-option
              v-for="author in authors"
              :key="author.id"
              :label="author.initials"
              :value="parseInt(author.id, 10)"
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
      <el-form-item>
        <el-upload @change="handleFileChange">
          <el-button size="small" type="primary">Choose File</el-button>
          <div slot="tip" class="el-upload__tip">Only jpg/png files allowed</div>
        </el-upload>
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

.el-form--label-top .el-form-item {
  width: -webkit-fill-available;
  margin-bottom: 0;
}
</style>