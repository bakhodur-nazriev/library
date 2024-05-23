<script setup>
import {computed, ref, onMounted} from 'vue';
import SampleTable from "../../components/SampleTable.vue";
import i18n from "../../i18n.js";
import DeleteUserModal from "../../components/modals/users/DeleteUserModal.vue";
import AddUserModal from "../../components/modals/users/AddUserModal.vue";
import EditUserModal from "../../components/modals/users/EditUserModal.vue";
import axios from "axios";
import router from "../../router/index.js";
import {ElMessage} from 'element-plus';

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
const loading = ref(false);
const getUsers = async () => {
  loading.value = true;
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Methods': '*',
    'Access-Control-Allow-Origin': '*',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .get(`/admin/users?per_page=${itemsPerPage.value}&page=${currentPage.value}&order=desc`, {headers})
      .then(res => {
        loading.value = false;
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
        ElMessage.error(err.response.data);
      })
};
const openAddUser = () => {
  showAddModal.value = true;
};
const openDeleteModal = (userId) => {
  selectedUserId.value = userId;
  showDeleteModal.value = true;
};
const closeDeleteModal = () => {
  showDeleteModal.value = false;
};
const openEditModal = (user) => {
  selectedUser.value = user;
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
const sendToPrint = () => {
  const usersDataForPrint = tableRows.value.map(user => ({
    name: user.name,
    email: user.email,
    password: '********'
  }));

  printUsers(usersDataForPrint);
}
const printUsers = (usersData) => {
  let printWindow = window.open('', '_blank');
  printWindow.document.write('<html><head><title>Список пользователей</title>');
  printWindow.document.write('<style>');
  printWindow.document.write('.table-for-print {');
  printWindow.document.write('width: 100%;');
  printWindow.document.write('border-collapse: collapse;');
  printWindow.document.write('margin-bottom: 20px;');
  printWindow.document.write('}');
  printWindow.document.write('.table-for-print th, .table-for-print td {');
  printWindow.document.write('border: 1px solid #000;');
  printWindow.document.write('padding: 6px;');
  printWindow.document.write('text-align: left;');
  printWindow.document.write('}');
  printWindow.document.write('.table-for-print th {');
  printWindow.document.write('background-color: #ccc;');
  printWindow.document.write('}');
  printWindow.document.write('</style>');
  printWindow.document.write('</head><body>');

  printWindow.document.write('<h1>Список пользователей</h1>');
  printWindow.document.write('<table class="table-for-print"><tr><th>Имя</th><th>Email</th><th>Пароль</th></tr>');

  usersData.forEach(user => {
    printWindow.document.write(`<tr><td>${user.name}</td><td>${user.email}</td><td>${user.password}</td></tr>`);
  });

  printWindow.document.write('</table>');
  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}

onMounted(() => {
  getUsers();
})
</script>

<template>
  <div class="main-authors__block">
    <h2 class="main-title">{{ $t('label.users') }}</h2>
    <button class="add-button" @click="openAddUser">{{ $t('buttons.add') }}</button>
    <button class="print-btn" @click="sendToPrint">{{ $t('buttons.print') }}</button>
    <SampleTable
        v-loading="loading"
        :element-loading-text="$t('loading')"
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
        :user-id="selectedUserId"
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

  .add-button, .print-btn {
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

  .add-button {
    margin-right: 15px;
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