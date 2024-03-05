<script setup>
import {ref} from "vue";

const books = ref(null);

const getBooks = () => {

};
</script>

<template>
  <div class="container">
    <section class="header">
      <img src="../assets/slide4.jpg" alt="">
      <div class="title-bar">
        <ul>
          <li></li>
          <li></li>
        </ul>
      </div>
      <h1 class="book-section_title">{{ $t('label.books_latest') }}</h1>
    </section>

    <div>
      <div class="filter-side">
        <h2>{{ $t('titles.filter.books.title') }}</h2>
        <span>{{ $t('titles.filter.books.subtitle') }}</span>
        <ul>
          <li>{{ $t('titles.filter.books.items.music') }}</li>
          <li>{{ $t('titles.filter.books.items.marketing') }}</li>
          <li>{{ $t('titles.filter.books.items.politics') }}</li>
          <li>{{ $t('titles.filter.books.items.creative') }}</li>
          <li>{{ $t('titles.filter.books.items.mathematics') }}</li>
          <li>{{ $t('titles.filter.books.items.geography') }}</li>
          <li>{{ $t('titles.filter.books.items.technology') }}</li>
        </ul>
        <button>{{ $t('buttons.see_all') }}</button>
      </div>
      <div class="books-section">
        <p>Search Box</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 1280px;
  margin: 0 auto;

  .book-section_title {
    position: absolute;
    text-align: center;
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 42px;
    font-weight: bold;
    color: var(--color-white);
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    left: 0;
    top: 45%;
    width: 100%;
  }

  .header {
    position: relative;
    display: flex;
    justify-content: center;
    margin-bottom: 40px;

    img {
      height: auto;
      filter: brightness(0.4);
    }
  }

  .title-bar {
    display: flex;
    justify-content: center;
    position: absolute;
    background-color: var(--color-white);
    height: 2px;
    max-width: 400px;
    width: 100%;
    color: var(--color-white);
    top: 55%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    z-index: 100;

    ul {
      position: absolute;
      padding: 0;
      margin: 0;
      list-style: none;
      display: flex;
      justify-content: center;
      gap: 12px;
      left: 0;
      top: 50%;
      -webkit-transform: translateY(-50%);
      transform: translateY(-50%);
      width: 100%;

      li {
        width: 12px;
        height: 12px;
        background-color: var(--color-white);
        transform: rotate(45deg)
      }
    }
  }
}
</style>