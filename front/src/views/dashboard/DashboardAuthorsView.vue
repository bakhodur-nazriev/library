<script setup>
import {computed, ref} from 'vue';
import SampleTable from "../../components/SampleTable.vue";
import i18n from "../../i18n.js";
import SampleDeleteModal from "../../components/modals/SampleDeleteModal.vue";

const tableHeaders = computed(() => {
  return [
    i18n.global.t('titles.table_titles.authors.name'),
    i18n.global.t('titles.table_titles.authors.date_of_birth'),
    i18n.global.t('titles.table_titles.authors.biography'),
    i18n.global.t('titles.table_titles.authors.nationality')
  ];
});
const tableRows = ref([
  ['John Jackson', '30.12.1932', 'Just Test', 'American'],
  ['Anna Watson', '32.12.1990', 'Just Test', 'Canadian'],
  ['Mike London', '11.11.1979', 'Just Test', 'Russian'],
  ['Amilly Jackson', '10.10.1939', 'Just Test', 'Germany'],
  ['Shavkat Nazriev', '09.12.1956', 'Just Test', 'Tajikistan']
]);

const addAuthor = () => {
  console.log('added authors');
}
</script>

<template>
  <div class="main-authors__block">
    <h2 class="main-title">{{ $t('label.authors') }}</h2>
    <button class="add-button" @click="addAuthor">{{ $t('buttons.add') }}</button>
    <SampleTable :rows="tableRows" :headers="tableHeaders"></SampleTable>
    <SampleDeleteModal/>
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