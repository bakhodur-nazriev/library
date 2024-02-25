<script setup>
import {computed, ref} from "vue";
import SampleTable from "../../components/SampleTable.vue";
import i18n from "../../i18n.js";
import DeleteModal from "../../components/modals/DeleteModal.vue";
import AddBooksModal from "../../components/modals/AddBooksModal.vue";

const tableHeaders = computed(() => {
  return [
    i18n.global.t('titles.table_titles.books.name'),
    i18n.global.t('titles.table_titles.books.description'),
    i18n.global.t('titles.table_titles.books.pages'),
    i18n.global.t('titles.table_titles.books.publisher')
  ];
});
const tableRows = ref([
  ['Jack London', 'Book about Jack London', 250, 'Adam'],
  ['Dukhtari Otash', 'Book about one old bad person', 230, 'Isa'],
  ['Robinson Crusoe', 'Book about how man survive in the Ireland', 322, 'Vlad'],
  ['The Legend', 'New book that take peoples heart', 422, 'Alexander'],
  ['Rich father and Poor father', 'Book about nothing', 522, 'Bakhodur']
]);

const addBook = () => {
  showAddModal.value = true;
};

const showDeleteModal = ref(false);
const showAddModal = ref(false);
const closeDeleteModal = () => {
  showDeleteModal.value = true;
};
const cancelAddModal = () => {
  showAddModal.value = false;
}
</script>

<template>
  <div class="main-books__block">
    <h2 class="main-title">{{ $t('label.books') }}</h2>
    <button class="add-button" @click="addBook">{{ $t('buttons.add') }}</button>
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