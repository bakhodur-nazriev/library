<script setup>
import {computed, onMounted, ref} from "vue";
import axios from "axios";
import i18n from "../../i18n.js";
import {getFormData} from "../../utils.js";
import SampleTable from "../../components/SampleTable.vue";
import DeleteModal from "../../components/modals/DeleteModal.vue";
import AddBooksModal from "../../components/modals/AddBooksModal.vue";

const tableHeaders = computed(() => {
  return [
    i18n.global.t('titles.table_titles.books.id'),
    i18n.global.t('titles.table_titles.books.name'),
    i18n.global.t('titles.table_titles.books.author'),
    i18n.global.t('titles.table_titles.books.description'),
    i18n.global.t('titles.table_titles.books.isbn'),
    i18n.global.t('titles.table_titles.books.publisher'),
    i18n.global.t('titles.table_titles.books.genre'),
    i18n.global.t('titles.table_titles.books.language'),
    i18n.global.t('titles.table_titles.books.pages'),
    i18n.global.t('titles.table_titles.books.publish_date'),
    i18n.global.t('titles.table_titles.books.add_date'),
  ];
});
const tableRows = ref([]);
const showDeleteModal = ref(false);
const showAddModal = ref(false);
const openAddBookModal = () => {
  showAddModal.value = true;

};
const closeDeleteModal = () => {
  showDeleteModal.value = true;
};
const cancelAddModal = () => {
  showAddModal.value = false;
}
const getBooks = async () => {
  const authToken = localStorage.getItem("token");
  const headers = {
    "Content-type": "applications/json",
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get('/admin/books?per_page=2&page=1', {headers})
      .then(res => {
        tableRows.value = res.data.data.map(book => ({
          id: book.id,
          title: book.title,
          author: book.author,
          description: book.description,
          isbn: book.ISBN,
          publisher: book.publisher,
          genre: book.genre,
          language: book.language,
          pages: book.pages,
          published_at: book.published_at,
          create_at: book.created_at
        }));
        console.log(res.data.data);
      })
      .catch(err => {
        console.log(err);
      })
}
const editBook = async (id) => {
  const payload = getFormData({});
  const authToken = localStorage.getItem('token');
  const headers = {
    "Content-type": "application/json",
    "Authorization": `Bearer ${authToken}`
  };

  await axios
      .patch('/admin/book' + id, payload, {headers})
      .then(res => {
        console.log(res);
      })
      .catch(err => {
        console.log(err);
      })
}
const removeBook = async (id) => {
  const authorToken = localStorage.getItem('token');
  const headers = {
    "Content-type": "applications/json",
    "Authorization": `Bearer ${authorToken}`
  };

  await axios
      .delete('/admin/books' + id, {headers})
      .then(res => {
        console.log(res);
      })
      .catch(err => {
        console.log(err);
      })
};

onMounted(() => {
  getBooks();
});
</script>

<template>
  <div class="main-books__block">
    <h2 class="main-title">{{ $t('label.books') }}</h2>
    <button class="add-button" @click="openAddBookModal">{{ $t('buttons.add') }}</button>
    <SampleTable :rows="tableRows" :headers="tableHeaders"/>
    <AddBooksModal v-if="showAddModal" @cancel="cancelAddModal"/>
    <DeleteModal v-if="showDeleteModal" @close="closeDeleteModal"/>
  </div>
</template>

<style scoped lang="scss">
.main-books__block {
  padding: 20px;

  .main-title {
    margin-top: 0;
  }

  .add-button {
    background-color: var(--primary-color);
    border: none;
    font-size: 14px;
    color: var(--white-color);
    margin-bottom: 10px;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
    box-shadow: 0 6px 10px -4px rgba(0, 0, 0, .15);

    &:hover {
      opacity: 0.9;
    }
  }
}
</style>