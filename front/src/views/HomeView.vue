<script setup>
import SampleTab from "../components/SampleTab.vue";
import {onMounted, ref} from "vue";
import axios from "axios";

const books = ref(null);
const getBooks = async () => {
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get('/books/?per_page=2&page=1&order=asc', {headers})
      .then(res => {
        console.log(res);
        if (res.status === 200 || res.status === 201) {
          books.value = res.data.data;
        }
      })
      .catch(err => {
        console.log(err);
      })
}
const readMore = () => {
}

onMounted(() => {
  getBooks();
});
</script>

<template>
  <div class="container">
    <section class="header">
      <img src="../assets/slide4.jpg" alt="">
      <SampleTab/>
    </section>

    <p class="book-section_title">{{ $t('label.books_latest') }}</p>
    <section class="books-section">
      <ul class="books-list">
        <li v-for="(book, i) in books" :key="i" class="books-list_item">
          <!--{{ book }}-->
          <img :src="book.img" :alt="book.name">
          <div class="right-block">
            <h3 class="book-name">{{ book.title }}</h3>
            <h4 class="book-author">{{ $t('label.authors') }}: {{ book.author }}</h4>

            <p class="book-description">{{ book.description }}</p>

            <button
                type="button"
                @click="readMore"
                class="read-more_btn"
            >
              {{ $t('buttons.read_more') }}
            </button>
          </div>
        </li>
      </ul>
    </section>

    <p class="author-section_title">{{ $t('label.authors_latest') }}</p>
    <section class=""></section>
  </div>
</template>

<style scoped lang="scss">
.container {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 1280px;
  margin: 0 auto;

  .book-section_title,
  .author-section_title {
    text-align: center;
    margin-top: 0;
    margin-bottom: 30px;
    font-size: 32px;
    font-weight: bold;
  }

  .header {
    position: relative;
    display: flex;
    justify-content: center;
    margin-bottom: 40px;

    img {
      width: 100vw;
      height: auto;
      filter: brightness(0.4);
      //position: absolute;
      //left: 0;
    }
  }

  .books-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
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

      .right-block {
        display: flex;
        flex-direction: column;
      }

      .read-more_btn {
        background-color: var(--color-primary);
        color: var(--color-white);
        border-radius: 5px;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        width: max-content;
      }

      img {
        width: 150px;
      }

      .book-name {
        font-size: 18px;
        margin: 0;
      }

      .book-author {
        margin: 0;
        font-size: 14px;
      }

      .book-description {
        flex-grow: 1;
      }
    }
  }
}
</style>
