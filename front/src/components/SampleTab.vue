<script setup>
import LoupeIcon from "./icons/LoupeIcon.vue";
import {computed, ref} from "vue";
import i18n from "../i18n.js";

const tabs = computed(() => {
  return [
    {title: i18n.global.t('label.books'), content: i18n.global.t('placeholders.search_book')},
    {title: i18n.global.t('label.authors'), content: i18n.global.t('placeholders.search_author')},
    {title: i18n.global.t('label.publisher'), content: i18n.global.t('placeholders.search_publisher')}
  ]
});

let currentTab = ref(0);
</script>

<template>
  <section class="search-block">
    <div class="tabs">
      <div
          class="tabs-title"
          v-for="(tab, index) in tabs"
          :key="index"
          @click="currentTab = index"
          :class="{ 'active': index === currentTab }"
      >
        {{ tab.title }}
      </div>

      <div class="divider"></div>
      <div
          class="input-group"
          v-for="(tab, index) in tabs"
          :key="index"
      >
        <div class="" v-if="index === currentTab">
          <input
              type="search"
              :placeholder="tab.content"
          >
          <div class="input-group-btn">
            <button type="button" class="search-btn">
              <LoupeIcon/>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped lang="scss">
.tabs {
  display: flex;
  flex-direction: column;

  &-title {
    cursor: pointer;
    height: fit-content;
    display: flex;
  }

  .active {
    background-color: #007bff;
    color: #fff;
  }
}

.search-block {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: var(--white-color);
  border-radius: 8px;
  height: 150px;
  width: 500px;
  z-index: 15;
  position: fixed;
  top: 300px;

  .input-group {
    display: flex;
    width: 100%;
    padding: 30px;

    .search-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: var(--primary-color);
      height: 40px;
      min-width: 80px;
      padding: 5px;
      margin: 0;
      border-top-right-radius: 4px;
      border-bottom-right-radius: 4px;
      border: none;
      cursor: pointer;

      svg {
        color: var(--white-color);
      }
    }

    input {
      outline: none;
      height: 40px;
      padding: 6px 12px;
      font-size: 13px;
      line-height: 1.42857143;
      color: #555;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
      border-right: none;
      border-top-left-radius: 4px;
      border-bottom-left-radius: 4px;
      width: inherit;
    }
  }
}

.divider {
  height: 1px solid var(--secondary-color);
  width: 100%;
}
</style>