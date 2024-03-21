<script setup>
import {onMounted, ref, watch} from "vue";
import axios from "axios";
import SampleBook from "../components/SampleBook.vue";
import CustomDivider from "../components/CustomDivider.vue";
import MainSearchTabs from "../components/MainSearchTabs.vue";
import SampleAuthor from "../components/SampleAuthor.vue";
import Navbar from "../components/Navbar.vue";

const books = ref(null);
const authors = ref(null);
const loading = ref(false);
const search = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(6);
const getBooks = async () => {
  loading.value = true;
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
    'Authorization': `Bearer ${authToken}`
  };

  let url = `/books/?per_page=${itemsPerPage.value}&page=${currentPage.value}&order=desc`;

  if (search.value.trim() !== '') {
    url = `/books/search/${encodeURIComponent(search.value.trim())}`;
  }

  await axios
      .get(url, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          books.value = res.data.data;
        }
      })
      .catch(err => {
        console.log(err);
      })
      .finally(() => loading.value = false);
};
const getAuthors = async () => {
  loading.value = true;
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get('/authors/?per_page=6&page=1&order=desc', {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          authors.value = res.data.data;
        }
      })
      .catch(err => {
        console.log(err);
      })
      .finally(() => loading.value = false);
};
const handleSearch = () => {
  getBooks();
}

watch(search, () => {
  handleSearch();
})

onMounted(() => {
  getBooks();
  getAuthors();
});
</script>

<template>
  <div
      class="container"
      v-loading.fullscreen.lock="loading"
      element-loading-background="rgba(0, 0, 0, 0.7)"
      :element-loading-text="$t('loading')"
  >
    <CustomDivider :title="$t('label.books_latest')"/>

    <section class="books-section">
      <ul class="books-list">
        <SampleBook :books="books"/>
      </ul>
    </section>

    <CustomDivider :title="$t('label.authors_latest')"/>

    <section class="authors-section">
      <ul class="authors-list">
        <SampleAuthor :authors="authors"/>
      </ul>
    </section>
  </div>
</template>

<style scoped lang="scss">
.container {
  padding: 20px 30px;
  max-width: 1280px;
  margin: 0 auto;

  .books-section {
    .books-list {
      padding: 0;
      margin: 0;
      list-style: none;
      width: 100%;
      display: grid;
      gap: 30px;
      grid-template-columns: repeat(3, 1fr);
    }
  }

  .authors-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    padding: 0;
    margin: 0;
    list-style: none;
    width: 100%;
    gap: 30px;
  }
}

@media (max-width: 1280px) {
  .container {
    .books-section {
      .books-list {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .authors-section {
      .authors-list {
        grid-template-columns: repeat(2, 1fr);
      }
    }
  }
}

@media (max-width: 780px) {
  .container {
    .books-section {
      .books-list {
        grid-template-columns: repeat(1, 1fr);
      }
    }

    .authors-section {
      .authors-list {
        grid-template-columns: repeat(1, 1fr);
      }
    }
  }
}
</style>
