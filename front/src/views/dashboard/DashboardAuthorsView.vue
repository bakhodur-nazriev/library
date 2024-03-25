<script setup>
import {computed, ref, onMounted, watch} from 'vue';
import SampleTable from "../../components/SampleTable.vue";
import i18n from "../../i18n.js";
import DeleteAuthorModal from "../../components/modals/authors/DeleteAuthorModal.vue";
import AddAuthorModal from "../../components/modals/authors/AddAuthorModal.vue";
import axios from "axios";
import EditAuthorModal from "../../components/modals/authors/EditAuthorModal.vue";
import router from "../../router/index.js";

const tableHeaders = computed(() => {
  return [
    i18n.global.t('titles.table_titles.authors.id'),
    i18n.global.t('titles.table_titles.authors.name'),
    i18n.global.t('titles.table_titles.authors.nationality'),
    i18n.global.t('titles.table_titles.authors.biography'),
    i18n.global.t('titles.table_titles.authors.date_of_birth'),
    i18n.global.t('titles.table_titles.authors.photo_link'),
  ];
});
const tableRows = ref([]);
const showDeleteModal = ref(false);
const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedAuthorId = ref(0);
const selectedAuthor = ref(null);
const loading = ref(true);
const currentPage = ref(1);
const itemsPerPage = ref(15);
const totalItems = ref(0);


const getAuthors = async () => {
  loading.value = true;
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Methods': '*',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get(`/authors?per_page=${itemsPerPage.value}&page=${currentPage.value}&order=desc`, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          tableRows.value = res.data.data.map(author => ({
            id: author.id,
            initials: author.initials,
            nationality: author.nationality,
            biography: author.biography,
            date_of_birth: author.date_of_birth,
            photo_link: author.photo_link
          }));
          totalItems.value = res.data.total;
        }
      })
      .catch(err => {
        if (err.response.status === 401) {
          router.push({name: 'login'});
          sessionStorage.removeItem('token');
          sessionStorage.removeItem('user');
        }
        console.log(err);
      })
      .finally(() => loading.value = false);
};
const openAddAuthor = () => {
  showAddModal.value = true;
};
const openDeleteModal = (authorId) => {
  selectedAuthorId.value = authorId;
  showDeleteModal.value = true;
};
const closeDeleteModal = () => {
  showDeleteModal.value = false;
};
const openEditModal = (author) => {
  selectedAuthor.value = author;
  showEditModal.value = true;
};
const closeEditModal = () => {
  showEditModal.value = false;
};
const closeAddModal = () => {
  showAddModal.value = false;
};
const handleCurrentPageChange = (newPage) => {
  currentPage.value = newPage;
  getAuthors();
};

onMounted(() => {
  getAuthors();
})
</script>

<template>
  <div class="main-authors__block">
    <h2 class="main-title">{{ $t('label.authors') }}</h2>
    <button class="add-button" @click="openAddAuthor">{{ $t('buttons.add') }}</button>
    <SampleTable
        v-loading="loading"
        :element-loading-text="$t('loading')"
        :rows="tableRows"
        :headers="tableHeaders"
        @openEditModal="openEditModal"
        @openDeleteModal="openDeleteModal"
    />
    <AddAuthorModal
        v-if="showAddModal"
        @close="closeAddModal"
        @reloadData="getAuthors"
    />
    <EditAuthorModal
        v-if="showEditModal"
        @close="closeEditModal"
        @reloadData="getAuthors"
        :selectedAuthor="selectedAuthor"
    />
    <DeleteAuthorModal
        v-if="showDeleteModal"
        @close="closeDeleteModal"
        @reloadData="getAuthors"
        :authorId="selectedAuthorId"
    />
    <div class="pagination-block">
      <el-pagination
          background
          :total="totalItems"
          class="custom-pagination"
          :page-size="itemsPerPage"
          layout="prev, pager, next"
          :current-page.sync="currentPage"
          @current-change="handleCurrentPageChange"
      />
    </div>
  </div>
</template>

<style scoped lang="scss">
.main-authors__block {
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