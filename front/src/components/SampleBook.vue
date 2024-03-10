<script setup>
import {ref} from "vue";
import axios from "axios";

const props = defineProps(['books']);
const selectedBook = ref(null);

const getBook = async (id) => {
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
    "Authorization": `Bearer ${authToken}`
  };

  await axios
      .get('/books/download-link/' + id, {headers})
      .then(res => {
        console.log(res.data);
        if (res.status === 200 || res.status === 201) {
          const downloadLink = res.data.file_path;
          window.open(downloadLink, '_blank');
        }
      })
      .catch(err => {
        console.log(err);
      })
}

</script>

<template>
  <ul class="books-list">
    <li v-for="(book, i) in books" :key="i" class="books-list_item">
      <img :src="book.cover_image" :alt="book.name" v-if="book.cover_image">
      <div v-else class="book-cover-block">
        <span>{{ $t('label.book_empty_cover') }}</span>
      </div>
      <div class="right-block">
        <h3 class="book-name">{{ book.title }}</h3>
        <h4 class="book-author">
          {{ book.authors.length > 1 ? $t('label.authors') : $t('label.author') }}:
          <span v-for="(author, index) in book.authors" :key="index">
            {{ author.initials }}{{ index < book.authors.length - 1 ? ', ' : '' }}
          </span>
        </h4>

        <p class="book-description">{{ book.description }}</p>

        {{ book.link }}
        <button
            type="button"
            class="read-more_btn"
            @click="getBook(book.id)"
        >
          {{ $t('buttons.read_more') }}
        </button>
      </div>
    </li>
    <!--    <PDFViewer :pdf-file="selectedBook"/>-->
  </ul>
</template>

<style scoped lang="scss">
.custom-file-upload {
  border: 1px solid #ccc;
  border-radius: 4px;
  display: inline-block;
  padding: 6px 12px;
  cursor: pointer;
  background: #4A8FED;
  padding: 10px;
  color: #fff;
  font: inherit;
  font-size: 16px;
  font-weight: bold;
}

input[type="file"] {
  display: none;
}

.books-list {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  padding: 0;
  margin: 0;
  list-style: none;
  width: 100%;
  gap: 30px;

  &_item {
    display: flex;
    padding: 20px 25px;
    border: 2px solid #d6d6d6;
    gap: 15px;
    border-radius: 5px;
    max-width: 410px;
    transition: all 0.3s ease 0s;

    &:hover {
      border: 2px solid var(--color-active-link);
    }

    .right-block {
      display: flex;
      flex-direction: column;
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

    img {
      width: 150px;
    }

    .book-name {
      font-size: 16px;
      margin: 0;
    }

    .book-author {
      margin: 0;
      font-size: 14px;

      span {
        font-weight: normal;
      }
    }

    .book-description {
      flex-grow: 1;
    }

    .book-cover-block {
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