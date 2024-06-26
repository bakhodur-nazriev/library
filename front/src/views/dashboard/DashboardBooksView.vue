<script setup>
import {computed, onMounted, ref} from "vue";
import axios from "axios";
import i18n from "../../i18n.js";
import SampleTable from "../../components/SampleTable.vue";
import BookDeleteModal from "../../components/modals/books/DeleteBookModal.vue";
import AddBookModal from "../../components/modals/books/AddBookModal.vue";
import EditBookModal from "../../components/modals/books/EditBookModal.vue";
import router from "../../router/index.js";

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
    i18n.global.t('titles.table_titles.books.cover_image'),
    i18n.global.t('titles.table_titles.books.publish_date'),
    i18n.global.t('titles.table_titles.books.added_date'),
  ];
});
const tableRows = ref([]);

const itemsPerPage = ref(15);
const currentPage = ref(1);
const totalItems = ref(0);
const loading = ref(false);

const showDeleteModal = ref(false);
const showEditModal = ref(false);

const showAddModal = ref(false);
const selectedBookId = ref(0);
const selectedBook = ref(null);
const openAddBookModal = () => {
  showAddModal.value = true;
};
const openDeleteModal = (bookId) => {
  selectedBookId.value = bookId;
  showDeleteModal.value = true;
};
const openEditModal = (book) => {
  selectedBook.value = book;
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
const getFormattedDate = (timestamp) => {
  const date = new Date(timestamp);
  const options = {year: 'numeric', month: '2-digit', day: '2-digit'};
  return date.toLocaleDateString(undefined, options).replace(/\./g, '-');
};
const getBooks = async () => {
  loading.value = true;
  const authToken = sessionStorage.getItem("token");
  const headers = {
    'Content-type': 'application/json',
    'Authorization': `Bearer ${authToken}`,
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*'
  };

  await axios
      .get(`/books?per_page=${itemsPerPage.value}&page=${currentPage.value}&order=desc`, {headers})
      .then(res => {
        tableRows.value = res.data.data.map(book => ({
          id: book.id,
          title: book.title,
          authors: book.authors.map(author => author.initials).join(', '),
          description: book.description,
          isbn: book.ISBN,
          publisher: book.publisher,
          genre: book.genre,
          language: book.language,
          pages: book.pages,
          cover_image: book.cover_image,
          published_at: book.published_at,
          create_at: getFormattedDate(book.created_at)
        }));

        totalItems.value = res.data.total;
      })
      .catch(err => {
        console.log(err);
        if (err.response.status === 401) {
          sessionStorage.removeItem('token');
          sessionStorage.removeItem('user');
          router.push({name: 'login'});
        }
        console.log(err);
      })
      .finally(() => loading.value = false);
};
const handleCurrentPageChange = (newPage) => {
  currentPage.value = newPage;
  getBooks();
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
        v-loading="loading"
        :element-loading-text="$t('loading')"
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
        :selectedBook="selectedBook"
    />
    <BookDeleteModal
        v-if="showDeleteModal"
        @close="closeDeleteModal"
        @reloadData="getBooks"
        :book-id="selectedBookId"
    />
    <div class="pagination-block">
      <el-pagination
          class="custom-pagination"
          background
          layout="prev, pager, next"
          :page-size="itemsPerPage"
          :total="totalItems"
          :current-page.sync="currentPage"
          @current-change="handleCurrentPageChange"
      />
    </div>
  </div>
</template>

<style scoped lang="scss">
.main-books__block {
  padding: 20px;

  .main-title {
    margin-top: 0;
  }

  .add-button {
    background-color: var(--color-primary);
    border: none;
    font-size: 14px;
    color: var(--color-white);
    margin-bottom: 30px;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
    box-shadow: 0 6px 10px -4px rgba(0, 0, 0, .15);

    &:hover {
      opacity: 0.9;
    }
  }

  .pagination-block {
    display: flex;
    justify-content: center;
    width: 100%;
    margin: 40px 0;

    .custom-pagination {
      --el-color-primary: var(--color-primary);
      --el-pagination-border-radius: 5px;
      --el-pagination-button-bg-color: #d6d6d6;
    }
  }
}
</style>