<script setup>
import {onMounted, ref, watch} from "vue";
import axios from "axios";
import SampleBook from "../components/SampleBook.vue";
import CustomDivider from "../components/CustomDivider.vue";
import MainSearchTabs from "../components/MainSearchTabs.vue";
import SampleAuthor from "../components/SampleAuthor.vue";

const books = ref(null);
const authors = ref(null);
const loading = ref(false);
const search = ref('');
const getBooks = async () => {
  loading.value = true;
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
    'Authorization': `Bearer ${authToken}`
  };

  let url = '/books/?per_page=6&page=1&order=desc';

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
    <section class="header">
      <img src="../assets/slide4.jpg" alt="">
      <MainSearchTabs/>
    </section>

    <CustomDivider :title="$t('label.books_latest')"/>

    <section class="books-section">
      <SampleBook :books="books" :gridColumns="3"/>
    </section>

    <CustomDivider :title="$t('label.authors_latest')"/>

    <section class="authors-section">
      <SampleAuthor :authors="authors"/>
    </section>
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

  .books-section {
    margin-bottom: 60px;

    ul {
      grid-template-columns: repeat(3, 1fr);
    }
  }

  .authors-section {
    margin-bottom: 50px;
  }
}
</style>
