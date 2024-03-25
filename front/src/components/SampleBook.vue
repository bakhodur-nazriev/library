<script setup>
import {ref, defineProps} from "vue";
import axios from "axios";
import Popup from "./Popup.vue";
import router from "../router/index.js";

const props = defineProps({
  books: Array,
});
const errorMessage = ref('');
const showError = ref(false);
const loading = ref(false);
const selectedBook = ref(null);

const getBook = async (id) => {
  loading.value = true;
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
    "Authorization": `Bearer ${authToken}`
  };

  await axios
      .get('/books/download/' + id, {headers, responseType: 'blob'})
      .then(res => {
        loading.value = false;

        if (res.status === 200 || res.status === 201) {

          let blob;
          if (res.data.type === 'image/vnd.djvu') {
            blob = new Blob([res.data], {type: 'image/vnd.djvu'});

          } else if (res.data.type === 'application/pdf') {
            blob = new Blob([res.data], {type: 'application/pdf'});

          } else if (res.data.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            blob = new Blob([res.data], {type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'});

          } else if (res.data.type === 'application/epub+zip') {
            blob = new Blob([res.data], {type: 'application/epub+zip'});

          } else if (res.data.type === 'application/x-mobipocket-ebook') {
            blob = new Blob([res.data], {type: 'application/x-mobipocket-ebook'});

          } else {
            blob = new Blob([res.data], {type: 'application/octet-stream'});
          }

          const downloadLink = URL.createObjectURL(blob);
          window.open(downloadLink, '_blank');
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
}
</script>

<template>
  <li v-for="(book, i) in books" :key="i" class="books-list_item">
    <img class="book-image" :src="book.cover_image" :alt="book.name" v-if="book.cover_image">
    <div v-else class="book-cover-block">
      <span>{{ $t('label.book_empty_cover') }}</span>
    </div>
    <div class="right-block">
      <div>
        <h3 class="book-name">{{ book.title }}</h3>
        <h4 class="book-author">
          {{ book.authors.length > 1 ? $t('label.authors') : $t('label.author') }}:
          <span v-for="(author, index) in book.authors" :key="index">
            {{ author.initials }}{{ index < book.authors.length - 1 ? ', ' : '' }}
          </span>
        </h4>

        <p class="book-description">{{ book.description }}</p>
      </div>

      <div>
        <button
            type="button"
            class="read-more_btn"
            @click="getBook(book.id)"
        >
          {{ $t('buttons.read_more') }}
        </button>
      </div>
    </div>
  </li>

  <Popup v-if="showError" :message="errorMessage"/>
</template>

<style scoped lang="scss">
.books-list_item {
  display: flex;
  padding: 20px 25px;
  border: 2px solid #d6d6d6;
  gap: 15px;
  border-radius: 5px;
  transition: all 0.3s ease 0s;

  &:hover {
    border: 2px solid var(--color-active-link);
  }

  .right-block {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 10px;
  }

  .read-more_btn {
    background-color: var(--color-primary);
    color: var(--color-white);
    border-radius: 5px;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    width: max-content;
    font-family: inherit;
  }


  .book {
    &-name {
      font-size: 16px;
      margin: 0;
    }

    &-author {
      margin-top: 6px;
      font-size: 14px;

      span {
        font-weight: normal;
      }
    }

    &-description {
      font-size: 14px;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 6;
      overflow: hidden;
    }

    &-image {
      width: 150px;
      border-radius: 5px;
    }

    &-cover-block {
      background: rgba(0, 0, 0, 0.8);
      min-width: 152px;
      max-width: 152px;
      height: 243px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 5px;
      color: var(--color-white);
    }
  }
}
</style>