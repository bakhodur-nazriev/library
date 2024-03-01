<script setup>
import {computed, ref, onMounted, watch} from 'vue';
import SampleTable from "../../components/SampleTable.vue";
import i18n from "../../i18n.js";
import DeleteAuthorModal from "../../components/modals/authors/DeleteAuthorModal.vue";
import AddAuthorModal from "../../components/modals/authors/AddAuthorModal.vue";
import axios from "axios";
// import EditAuthorModal from "../../components/modals/authors/EditAuthorModal.vue";

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

const getAuthors = async () => {
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get('/authors?per_page=15&page=1', {headers})
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
        }
      })
      .catch(err => {
        console.log(err);
      })
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
const openEditModal = () => {
  showEditModal.value = true;
};
const closeEditModal = () => {
  showEditModal.value = false;
};
const closeAddModal = () => {
  showAddModal.value = false;
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
    <!--    <EditAuthorModal-->
    <!--        v-if="showEditModal"-->
    <!--        @close="closeEditModal"-->
    <!--        :author="selectedAuthorId"-->
    <!--    />-->
    <DeleteAuthorModal
        v-if="showDeleteModal"
        @close="closeDeleteModal"
        @reloadData="getAuthors"
        :authorId="selectedAuthorId"
    />
  </div>
</template>

<style scoped lang="scss">
.main-authors__block {
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