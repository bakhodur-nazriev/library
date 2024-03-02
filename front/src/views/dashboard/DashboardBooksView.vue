<script setup>
import {computed, onMounted, ref} from "vue";
import axios from "axios";
import i18n from "../../i18n.js";
import SampleTable from "../../components/SampleTable.vue";
import BookDeleteModal from "../../components/modals/books/DeleteBookModal.vue";
import AddBookModal from "../../components/modals/books/AddBookModal.vue";
import EditBookModal from "../../components/modals/books/EditBookModal.vue";

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
    i18n.global.t('titles.table_titles.books.added_date'),
  ];
});
const tableRows = ref([]);

const showDeleteModal = ref(false);
const showEditModal = ref(false);

const showAddModal = ref(false);
const selectedBookId = ref(0);
const selectedBook = ref({});
const openAddBookModal = () => {
  showAddModal.value = true;
};
const openDeleteModal = (bookId) => {
  selectedBookId.value = bookId;
  showDeleteModal.value = true;
};
const openEditModal = (selectedItem) => {
  selectedBook.value = {
    id: selectedItem.id,
    title: selectedItem.title,
    author: selectedItem.author,
    description: selectedItem.description,
    isbn: selectedItem.isbn,
    publisher: selectedItem.publisher,
    genre: selectedItem.genre,
    language: selectedItem.language,
    pages: selectedItem.pages,
    published_at: selectedItem.published_at,
    created_at: selectedItem.create_at
  };
  showEditModal.value = true;
}
const closeDeleteModal = () => {
  showDeleteModal.value = false;
};
const closeEditModal = () => {
  showEditModal.value = false;
};
const cancelAddModal = () => {
  showAddModal.value = false;
};
const getBooks = async () => {
  const authToken = sessionStorage.getItem("token");
  const headers = {
    'Content-type': 'applications/json',
    'Authorization': `Bearer ${authToken}`,
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*'
  };

  await axios
      .get('/books?per_page=15&page=1', {headers})
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
    <SampleTable
        :rows="tableRows"
        :headers="tableHeaders"
        @openEditModal="openEditModal"
        @openDeleteModal="openDeleteModal"
    />
    <AddBookModal
        v-if="showAddModal"
        @cancel="cancelAddModal"
        @reloadData="getBooks"
    />
    <EditBookModal
        v-if=showEditModal
        @close="closeEditModal"
        @reloadData="getBooks"
        :book="selectedBook"
    />
    <BookDeleteModal
        v-if="showDeleteModal"
        @close="closeDeleteModal"
        @reloadData="getBooks"
        :book-id="selectedBookId"
    />
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