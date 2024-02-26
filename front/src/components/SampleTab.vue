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
        <input type="search" :placeholder="tab.content">
        <button type="button" class="search-btn">
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
  background-color: var(--white-color);
  border-radius: 8px;
  width: 500px;
  z-index: 15;
  position: fixed;
  top: 300px;

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
        color: var(--secondary-color);
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
        width: 100%;
      }
    }
  }
}
</style>