<script setup>
import {onMounted, ref} from "vue";
import FilterIcon from "../components/icons/FilterIcon.vue";
import axios from "axios";
import LoupeIcon from "../components/icons/LoupeIcon.vue";
import SampleBook from "../components/SampleBook.vue";

const books = ref(null);
const search = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(12);
const totalItems = ref(0);
const loading = ref(false);

const getBooks = async () => {
  loading.value = true;
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get(`/books/?per_page=${itemsPerPage.value}&page=${currentPage.value}&order=desc`, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          books.value = res.data.data;
          totalItems.value = res.data.total;
        }
      })
      .catch(err => {
        console.log(err);
      })
      .finally(() => loading.value = false);
}
const handleCurrentPageChange = (newPage) => {
  currentPage.value = newPage;
  getBooks();
};

onMounted(() => {
  getBooks();
});
</script>

<template>
  <div
      class="container"
      v-loading.fullscreen.lock="loading"
      element-loading-background="rgba(0, 0, 0, 0.7)"
      :element-loading-text="$t('loading')"
  >
    <section class="header">
      <img src="../assets/slide4.jpg" alt="">
      <div class="title-bar">
        <ul>
          <li></li>
          <li></li>
        </ul>
      </div>
      <section class="main-title_section">
        <h1>{{ $t('titles.banner.books') }}</h1>
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
        <button class="filter-all_btn">{{ $t('buttons.see_all') }}</button>
      </aside>

      <div class="books-section">
        <div class="search-box_section">
          <h4 class="main-right-side-title">{{ $t('titles.books.search_box') }}</h4>
          <div class="search-box">
            <input
                type="search"
                :placeholder="$t('placeholders.search_book')"
                v-model="search"
            />
            <button class="search-btn">
              <LoupeIcon/>
            </button>
          </div>
        </div>

        <section class="book-block">
          <SampleBook :books="books"/>
        </section>

        <div class="pagination-block">
          <el-pagination
              class="custom-pagination"
              background
              layout="prev, pager, next"
              :total="totalItems"
              :current-page.sync="currentPage"
              @current-change="handleCurrentPageChange"
          />
        </div>
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
    top: 43%;
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
    top: 58%;
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
    justify-content: center;

    .sidebar {
      width: 200px;
      padding: 0 15px;
      display: flex;
      flex-direction: column;
      gap: 20px;

      .filter-all_btn {
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
          margin: 0;
        }
      }

      hr {
        width: 100%;
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
          display: flex;
          align-items: center;

          &::before {
            margin-right: 10px;
            content: '';
            width: 10px;
            height: 10px;
            background-color: var(--color-gray);
            transition: all 0.3s ease 0s;
          }

          &:hover {
            color: var(--color-active-link);
          }

          &:hover::before {
            background-color: var(--color-active-link);
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

      .search-box_section {
        margin-bottom: 40px;

        .search-box {
          display: flex;
        }

        input {
          border-top-left-radius: 5px;
          border-bottom-left-radius: 5px;
          height: 40px;
          border: 2px solid #d6d6d6;
          border-right: 0;
          padding: 0 10px;
          width: 100%;
          font-family: inherit;

          &:focus {
            border-color: #66afe9;
            outline: 0;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
          }
        }

        .search-btn {
          background-color: var(--color-primary);
          border-top-right-radius: 5px;
          border-bottom-right-radius: 5px;
          height: 40px;
          min-width: 80px;
          padding: 5px;
          border: none;
          display: flex;
          align-items: center;
          justify-content: center;

          svg {
            color: var(--color-white);
          }
        }
      }

      .pagination-block {
        display: flex;
        justify-content: end;
        margin: 40px 0;


        .custom-pagination {
          --el-color-primary: var(--color-primary);
          --el-pagination-border-radius: 5px;
          --el-pagination-button-bg-color: #d6d6d6;
        }
      }
    }
  }
}

</style>