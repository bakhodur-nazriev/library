<script setup>
const props = defineProps(['headers', 'rows']);
const emit = defineEmits(['edit', 'remove', 'openDeleteModal', 'openEditModal']);

const openEditModal = (selectedBook) => {
  emit('openEditModal', selectedBook);
};
const openDeleteModal = (bookId) => {
  emit('openDeleteModal', bookId);
};
</script>

<template>
  <table>
    <thead>
    <tr>
      <th v-for="(header, i) in props.headers" :key="i">{{ header }}</th>
      <th>{{ $t('titles.table_titles.books.actions') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(row, rowIndex) in rows" :key="rowIndex">
      <td v-for="(cell, cellIndex) in row" :key="cellIndex">
        {{ cell }}
      </td>
      <td class="actions-column">
        <button class="edit-button" @click="openEditModal()">{{ $t('buttons.edit') }}</button>
        <button class="remove-button" @click="openDeleteModal(row.id)">{{ $t('buttons.delete') }}</button>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<style scoped lang="scss">
table {
  background-color: var(--white-color);
  padding: 15px;
  border-radius: 10px;
  width: 100%;
  box-shadow: 0 6px 10px -4px rgba(0, 0, 0, .15);

  tr {
    //display: table-row;
    //vertical-align: inherit;
    //border-color: inherit;
    border-bottom: 1px solid #000;

    td, th {
      font-size: 14px;
      padding: 8px;
      text-align: left;
    }
  }
}

.actions-column {
  display: flex;
  gap: 10px;
  width: max-content;
  font-size: 14px;
}

.edit-button {
  padding: 5px 8px;
  border: 1px solid #c6e2ff;
  background-color: var(--white-color);
  cursor: pointer;
  border-radius: 5px;
  font-size: 13px;
}

.remove-button {
  padding: 5px 8px;
  cursor: pointer;
  background-color: #fa4242;
  border: 1px solid #fa4242;
  color: var(--white-color);
  border-radius: 5px;
  font-size: 13px;
}
</style>