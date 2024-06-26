<script setup>
import {ref} from 'vue';
import {getFormData} from "../../../utils.js";
import axios from "axios";
import router from "../../../router/index.js";

const emit = defineEmits(['close', 'reloadData']);
const author = ref({
  initials: '',
  nationality: '',
  biography: '',
  date_of_birth: '',
  photo_link: ''
});
const loading = ref(false);
const authorNationalities = [
  {label: 'Англичанин', value: 'Англичанин'},
  {label: 'Француз', value: 'Француз'},
  {label: 'Испанец', value: 'Испанец'},
  {label: 'Немец', value: 'Немец'},
  {label: 'Русский', value: 'Русский'},
  {label: 'Тоҷик', value: 'Тоҷик'},
];
const handleFileChange = (e) => {
  author.value.photo_link = e.target.files[0];
};
const addAuthor = async () => {
  loading.value = true;
  const payload = getFormData(author.value);
  const authToken = sessionStorage.getItem('token');
  const headers = {
    'Authorization': `Bearer ${authToken}`,
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': '*'
  };

  await axios
      .post('/admin/authors', payload, {headers})
      .then(res => {
        if (res.status === 200 || res.status === 201) {
          emit('reloadData', true);
          emitClose();
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
      .finally(() => loading.value = false);
};
const emitClose = () => {
  emit('close');
}
</script>

<template>
  <div
      @click="emitClose"
      v-loading="loading"
      class="modal-overlay"
      :element-loading-text="$t('loading')"
      element-loading-background="rgba(0, 0, 0, 0.7)"
  >
    <el-form
        label-width="auto"
        label-position="top"
        ref="formRef"
        style="max-width: 600px;"
        status-icon
        :model="author"
        class="modal"
        @click.stop
    >
      <h1 class="modal-title">{{ $t('label.add_author') }}</h1>
      <el-form-item :label="`${$t('titles.table_titles.authors.name')}`" prop="initials">
        <el-input v-model="author.initials"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.authors.nationality')}`"
          prop="nationality"
      >
        <el-select
            :placeholder="`${$t('titles.table_titles.authors.nationality')}`"
            v-model="author.nationality"
            filterable
            clearable
        >
          <el-option
              v-for="(nat, i) in authorNationalities"
              :key="i"
              :label="nat.label"
              :value="nat.value"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item :label="`${$t('titles.table_titles.authors.biography')}`" prop="biography">
        <el-input type="textarea" v-model="author.biography"/>
      </el-form-item>
      <el-form-item
          :label="`${$t('titles.table_titles.authors.date_of_birth')}`"
          prop="date_of_birth"
      >
        <el-date-picker
            v-model="author.date_of_birth"
            type="date"
            format="YYYY-MM-DD"
            :placeholder="`${$t('buttons.pick_a_date')}`"
        />
      </el-form-item>
      <el-form-item :label="`${$t('titles.table_titles.authors.photo_link')}`">
        <el-upload @change="handleFileChange">
          <el-button size="small" type="primary">{{ $t('titles.table_titles.authors.photo_link') }}</el-button>
          <div slot="tip" class="el-upload__tip">{{ $t('validation.only_jpg_png_files_allowed') }}</div>
        </el-upload>
      </el-form-item>

      <el-form-item>
        <el-button @click="addAuthor" :disabled="loading.value" type="primary">{{ $t('buttons.save') }}</el-button>
        <el-button @click="emitClose">{{ $t('buttons.cancel') }}</el-button>
      </el-form-item>
    </el-form>
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
  gap: 10px;
  overflow-y: auto;
  max-height: -webkit-fill-available;
  max-height: -moz-available;

  &-title {
    font-size: 26px;
    margin: 0;
  }

  .buttons-block {
    display: flex;
    justify-content: center;
    width: auto;
  }
}

.el-form--label-top .el-form-item {
  width: -webkit-fill-available;
  width: -moz-available;
  margin-bottom: 0;
}
</style>