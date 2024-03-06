<script setup>
import {onMounted, ref} from "vue";
import FilterIcon from "../components/icons/FilterIcon.vue";
import axios from "axios";

const books = ref(null);

const getBooks = async () => {
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get('/books/?per_page=6&page=1&order=desc', {headers})
      .then(res => {
        console.log(res.data.data);
        if (res.status === 200 || res.status === 201) {
          books.value = res.data.data;
        }
      })
      .catch(err => {
        console.log(err);
      })
}

onMounted(() => {
  getBooks();
});
</script>

<template>
  <div class="container">
    <section class="header">
      <img src="../assets/slide4.jpg" alt="">
      <div class="title-bar">
        <ul>
          <li></li>
          <li></li>
        </ul>
      </div>
      <section class="main-title_section">
        <h1>{{ $t('label.books_latest') }}</h1>
      </section>
    </section>

    <div class="main-content">
      <aside class="sidebar">
        <div class="filter-titles_block">
          <div class="filter-titles_block-first">
            <FilterIcon/>
            <h3>{{ $t('titles.filter.books.title') }}</h3>
          </div>
          <h4>{{ $t('titles.filter.books.subtitle') }}</h4>
        </div>

        <hr>

        <ul class="filter-list">
          <li class="filter-list_item">{{ $t('titles.filter.books.items.music') }}</li>
          <li class="filter-list_item">{{ $t('titles.filter.books.items.marketing') }}</li>
          <li class="filter-list_item">{{ $t('titles.filter.books.items.politics') }}</li>
          <li class="filter-list_item">{{ $t('titles.filter.books.items.creative') }}</li>
          <li class="filter-list_item">{{ $t('titles.filter.books.items.mathematics') }}</li>
          <li class="filter-list_item">{{ $t('titles.filter.books.items.geography') }}</li>
          <li class="filter-list_item">{{ $t('titles.filter.books.items.technology') }}</li>
        </ul>
        <button>{{ $t('buttons.see_all') }}</button>
      </aside>


      <div class="books-section">
        <h4 class="main-right-side-title">Search Box</h4>

        <section>
          <ul class="books-list">
            <li v-for="(book, i) in books" :key="i" class="books-list_item">
              <img :src="book.img" :alt="book.name">
              <div class="right-block">
                <h3 class="book-name">{{ book.title }}</h3>
                <h4 class="book-author">{{ $t('label.authors') }}: {{ book.author }}</h4>

                <p class="book-description">{{ book.description }}</p>

                <button
                    type="button"
                    class="read-more_btn"
                >
                  {{ $t('buttons.read_more') }}
                </button>
              </div>
            </li>
          </ul>
        </section>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.container {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 1280px;
  margin: 0 auto;

  .header {
    position: relative;
    display: flex;
    justify-content: center;
    margin-bottom: 40px;

    img {
      height: auto;
      filter: brightness(0.4);
    }
  }

  .main-title_section {
    position: absolute;
    left: 0;
    top: 45%;
    width: 100%;
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);

    h1 {
      margin: 0;
      font-size: 42px;
      font-weight: bold;
      color: var(--color-white);
    }
  }

  .title-bar {
    display: flex;
    justify-content: center;
    position: absolute;
    background-color: var(--color-white);
    height: 2px;
    max-width: 400px;
    width: 100%;
    color: var(--color-white);
    top: 55%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    z-index: 100;

    ul {
      position: absolute;
      padding: 0;
      margin: 0;
      list-style: none;
      display: flex;
      justify-content: center;
      gap: 12px;
      left: 0;
      top: 50%;
      -webkit-transform: translateY(-50%);
      transform: translateY(-50%);
      width: 100%;

      li {
        width: 12px;
        height: 12px;
        background-color: var(--color-white);
        transform: rotate(45deg)
      }
    }
  }

  .main-content {
    display: flex;

    .sidebar {
      max-width: 200px;
      padding: 0 15px;

      button {
        color: var(--color-white);
        background-color: var(--color-primary);
        padding: 10px;
        border-radius: 3px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease 0s;
        box-shadow: 0 0 0 0 #aaaaaa;
        min-width: 100px;

        &:hover {
          background-color: #1b3e98;
        }
      }

      .filter-titles_block {
        display: flex;
        flex-direction: column;

        &-first {
          display: flex;
          gap: 10px;
          margin-bottom: 45px;

          h3 {
            margin: 0;
            font-weight: normal;
            font-size: 24px;
            color: var(--color-gray);
          }
        }

        h4 {
          font-size: 18px;
          color: var(--color-gray);
          font-weight: normal;
          margin-top: 0;
          margin-bottom: 15px;
        }
      }

      hr {
        border: 0;
        border-top: 1px solid #eee;
      }

      .filter-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 0;
        margin: 0;
        list-style: none;
        font-size: 14px;

        &_item {
          cursor: pointer;
          transition: all 0.3s ease 0s;

          &:hover {
            color: var(--color-active-link);
          }
        }
      }
    }

    .books-section {
      padding: 0 15px;

      .main-right-side-title {
        margin: 5px 0 10px 0;
        font-size: 18px;
        color: var(--color-gray);
        font-weight: normal;
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
  }
}
</style>