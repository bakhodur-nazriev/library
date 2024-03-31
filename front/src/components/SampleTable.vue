<script setup>
const props = defineProps(['headers', 'rows']);
const emit = defineEmits(['openDeleteModal', 'openEditModal']);

const openEditModal = (selectedAuthor) => {
  emit('openEditModal', selectedAuthor);
};
const openDeleteModal = (bookId) => {
  emit('openDeleteModal', bookId);
};
</script>

<template>
  <div class="main-block_table">
    <table class="content-table">
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
          <button class="edit-button" @click="openEditModal(row)">{{ $t('buttons.edit') }}</button>
          <button class="remove-button" @click="openDeleteModal(row.id)">{{ $t('buttons.delete') }}</button>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped lang="scss">
.main-block_table {
  overflow-x: auto;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}

.content-table {
  min-width: 100%;
  font-size: 14px;
  background-color: var(--color-white);
  border-collapse: collapse;
  border-radius: 8px;
  overflow: hidden;

  th, td {
    padding: 12px 15px;
    max-width: 220px;
    overflow: auto;
  }

  thead tr {
    background-color: var(--color-primary);
    color: var(--color-white);
    text-align: left;
    font-weight: bold;

    th {
      text-wrap: nowrap;
    }
  }

  tbody {
    tr {
      border-bottom: 1px solid #dddddd;
      font-weight: 500;

      &:nth-of-type(even) {
        background-color: var(--bg-grey-color);
      }
    }
  }
}

.actions-column {
  display: flex;
  gap: 10px;
  width: max-content;
  font-size: 14px;

  .edit-button {
    padding: 5px 8px;
    border: 1px solid #c6e2ff;
    background-color: var(--color-white);
    cursor: pointer;
    border-radius: 5px;
    font-size: 13px;
  }

  .remove-button {
    padding: 5px 8px;
    cursor: pointer;
    background-color: #fa4242;
    border: 1px solid #fa4242;
    color: var(--color-white);
    border-radius: 5px;
    font-size: 13px;
  }
}
</style>