<script setup>
import {ref} from 'vue';
import {getFormData} from "../../../utils.js";
import axios from "axios";
import router from "../../../router/index.js";

const emit = defineEmits(['close', 'reloadData']);
const props = defineProps(['selectedAuthor']);
const loading = ref(false);

const author = ref({
  initials: props.selectedAuthor.initials,
  nationality: props.selectedAuthor.nationality,
  biography: props.selectedAuthor.biography,
  date_of_birth: props.selectedAuthor.date_of_birth,
  photo_link: props.selectedAuthor.photo_link
});

const handleFileChange = (e) => {
  author.value.photo_link = e.target.files[0];
};

const editData = async () => {
  loading.value = true;
  const payload = getFormData(author.value);
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*',
    'Authorization': `Bearer ${authToken}`
  };

  await axios
      .patch('/admin/authors/' + props.selectedAuthor.id, payload, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          emit('reloadData', true);
          emitClose();
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
const emitClose = () => {
  emit('close');
}
</script>

<template>
  <div
      class="modal-overlay"
      @click="emitClose"
      v-loading="loading"
      :element-loading-text="$t('loading')"
  >
    <div class="modal" @click.stop>
      <h1 class="modal-title">{{ $t('label.edit_author') }}</h1>
      <ul class="input-list">
        <li class="input-list__item">
          <input
              v-model="author.initials"
              type="text"
              :placeholder="`${$t('titles.table_titles.authors.name')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              v-model="author.nationality"
              type="text"
              :placeholder="`${$t('titles.table_titles.authors.nationality')}`"
          />
        </li>
        <li class="input-list__item">
          <textarea
              v-model="author.biography"
              :placeholder="`${$t('titles.table_titles.authors.biography')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              v-model="author.date_of_birth"
              type="date"
              :placeholder="`${$t('titles.table_titles.authors.date_of_birth')}`"
          />
        </li>
        <li class="input-list__item">
          <input
              @change="handleFileChange"
              type="file"
              :placeholder="`${$t('titles.table_titles.authors.photo_link')}`"
          />
        </li>
      </ul>
      <ul class="button-list">
        <li class="button-list__item">
          <button @click="editData">{{ $t('buttons.save') }}</button>
        </li>
        <li class="button-list__item">
          <button @click="emitClose">{{ $t('buttons.cancel') }}</button>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped lang="scss">
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 15;
}

.modal {
  background-color: var(--color-white);
  border-radius: 12px;
  width: 400px;
  padding: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;

  &-title {
    font-size: 26px;
    margin: 0;
  }

  .input-list,
  .button-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .input-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: inherit;

    &__item {
      input, textarea {
        background-color: var(--color-white);
        border-radius: 8px;
        border: 1px solid var(--color-gray);
        box-sizing: border-box;
        color: var(--color-gray);
        font-size: 14px;
        outline: none;
        padding: 12px 15px;
        transition: border-color .2s cubic-bezier(.645, .045, .355, 1);
        width: 100%;
      }
    }
  }

  .button-list {
    display: flex;
    justify-content: center;
    gap: 15px;

    &__item {
      button {
        font-size: 16px;
        border: none;
        background-color: var(--color-primary);
        border-radius: 8px;
        padding: 8px 15px;
        color: var(--color-white);
        cursor: pointer;
      }
    }
  }
}
</style>