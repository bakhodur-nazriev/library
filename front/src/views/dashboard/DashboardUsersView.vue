<script setup>
import {computed, ref, onMounted} from 'vue';
import SampleTable from "../../components/SampleTable.vue";
import i18n from "../../i18n.js";
import DeleteUserModal from "../../components/modals/users/DeleteUserModal.vue";
import AddUserModal from "../../components/modals/users/AddUserModal.vue";
import EditUserModal from "../../components/modals/users/EditUserModal.vue";
import axios from "axios";
import router from "../../router/index.js";

const tableHeaders = computed(() => {
  return [
    i18n.global.t('titles.table_titles.authors.id'),
    i18n.global.t('titles.table_titles.users.name'),
    i18n.global.t('titles.table_titles.users.email'),
    i18n.global.t('titles.table_titles.users.role'),
  ];
});
const tableRows = ref([]);
const showDeleteModal = ref(false);
const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedUserId = ref(0);
const selectedUser = ref(null);
const totalItems = ref(0);
const itemsPerPage = ref(12);
const currentPage = ref(1);

const getUsers = async () => {
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Methods': '*',
    'Access-Control-Allow-Origin': '*',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get(`/admin/users?per_page=15&page=${currentPage.value}`, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          tableRows.value = res.data.data.map(user => ({
            id: user.id,
            name: user.name,
            email: user.email,
            role: user.role
          }));
          totalItems.value = res.data.total;
        }
      })
      .catch(err => {
        if (err.response.status === 401) {
          sessionStorage.removeItem('token');
          sessionStorage.removeItem('user');
          router.push({name: 'login'});
        }
        console.log(err);
      })
};
const openAddUser = () => {
  showAddModal.value = true;
};
const openDeleteModal = (authorId) => {
  selectedUserId.value = authorId;
  showDeleteModal.value = true;
};
const closeDeleteModal = () => {
  showDeleteModal.value = false;
};
const openEditModal = (author) => {
  selectedUser.value = author;
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
  getUsers();
};

onMounted(() => {
  getUsers();
})
</script>

<template>
  <div class="main-authors__block">
    <h2 class="main-title">{{ $t('label.users') }}</h2>
    <button class="add-button" @click="openAddUser">{{ $t('buttons.add') }}</button>
    <SampleTable
        :rows="tableRows"
        :headers="tableHeaders"
        @openEditModal="openEditModal"
        @openDeleteModal="openDeleteModal"
    />
    <AddUserModal
        v-if="showAddModal"
        @cancel="closeAddModal"
        @reloadData="getUsers"
    />
    <EditUserModal
        v-if="showEditModal"
        @close="closeEditModal"
        @reloadData="getUsers"
        :selectedUser="selectedUser"
    />
    <DeleteUserModal
        v-if="showDeleteModal"
        @close="closeDeleteModal"
        @reloadData="getUsers"
        :authorId="selectedUserId"
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