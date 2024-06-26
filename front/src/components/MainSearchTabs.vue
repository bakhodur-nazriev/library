<script setup>
import LoupeIcon from "./icons/LoupeIcon.vue";
import {computed, ref, watch} from "vue";
import i18n from "../i18n.js";

const emit = defineEmits(['searchBook', 'searchAuthor']);
let currentTab = ref(0);
const searchBook = ref('');
const searchAuthor = ref('');
const tabs = computed(() => {
  return [
    {title: i18n.global.t('label.books'), content: i18n.global.t('placeholders.search_book')},
    {title: i18n.global.t('label.authors'), content: i18n.global.t('placeholders.search_author')},
  ]
});
const searchQuery = ref('');
const handleSearch = () => {
  const query = searchQuery.value;
  if (currentTab.value === 0) {
    emit('searchBook', query);
  } else {
    emit('searchAuthor', query);
  }
};
watch(searchQuery, () => {
  setTimeout(() => {
    handleSearch();
  }, 500);
});
</script>

<template>
  <section class="search-block">
    <div class="tabs">
      <ul class="tabs-title_list">
        <li
            class="tabs-title_list-item"
            v-for="(tab, index) in tabs"
            :key="index"
            @click="currentTab = index"
            :class="{ 'active': index === currentTab }"
        >
          {{ tab.title }}
        </li>
      </ul>

      <div
          class="tabs-content"
          v-for="(tab, index) in tabs"
          :key="index"
          v-show="index === currentTab"
      >
        <input
            type="search"
            v-model="searchQuery"
            :placeholder="tab.content"
        >
        <button @click="handleSearch" type="button" class="search-btn">
          <LoupeIcon/>
        </button>
      </div>
    </div>
  </section>
</template>

<style scoped lang="scss">
.search-block {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: var(--color-white);
  border-radius: 8px;
  width: 500px;
  z-index: 150;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  top: 170px;

  .tabs {
    width: inherit;
    height: inherit;

    &-title_list {
      height: fit-content;
      padding: 10px 10px 0;
      margin: 0;
      list-style-type: none;
      display: flex;
      gap: 15px;
      border-bottom: 1px solid #dddddd;

      &-item {
        width: max-content;
        padding: 10px 15px;
        font-size: 14px;
        cursor: pointer;
      }

      .active {
        color: var(--color-gray);
        position: relative;

        &::after {
          background-color: #2352c5;
          bottom: 0;
          box-shadow: 0 0 6px 0 #2352c5;
          content: "";
          height: 1px;
          left: 0;
          position: absolute;
          width: 100%;
        }
      }
    }

    &-content {
      display: flex;
      padding: 30px;

      .search-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--color-primary);
        height: 40px;
        min-width: 80px;
        padding: 5px;
        margin: 0;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
        border: none;
        cursor: pointer;

        svg {
          color: var(--color-white);
        }
      }

      input {
        font-family: inherit;
        outline: none;
        height: 40px;
        padding: 6px 12px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-right: none;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
        width: 100%;
      }
    }
  }
}

@media (max-width: 768px) {
  .search-block {
    width: 90%;
  }
}
</style>