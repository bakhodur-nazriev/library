<script setup>
import SampleTab from "../components/SampleTab.vue";
import {ref} from "vue";

const books = ref([
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  }, {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
  {
    name: "Just Test!",
    author: "Odam",
    description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor",
    img: 'src/assets/books_cover/img.png'
  },
]);

const readMore = () => {
  console.log();
}
</script>

<template>
  <div class="container">
    <section class="header">
      <img src="../assets/slide4.jpg" alt="">
      <SampleTab/>
    </section>

    <section class="books-section">
      <ul class="books-list">
        <li v-for="(book, i) in books" :key="i" class="books-list_item">
          <img :src="book.img" :alt="book.name">
          <div class="right-block">
            <h3 class="book-name">{{ book.name }}</h3>
            <h4 class="book-author">{{ $t('label.authors') }}: {{ book.author }}</h4>

            <p class="book-description">{{ book.description }}</p>

            <button type="button" @click="readMore" class="read-more_btn">{{ $t('buttons.read_more') }}</button>
          </div>
        </li>
      </ul>
    </section>
  </div>
</template>

<style scoped lang="scss">
.container {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 1280px;
  margin: 0 auto;

  .header {
    position: relative;
    display: flex;
    justify-content: center;
    margin-bottom: 40px;

    img {
      width: 100vw;
      height: auto;
      filter: brightness(0.4);
    }
  }

  .books-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    padding: 0;
    margin: 0;
    list-style: none;
    width: 100%;
    gap: 30px;

    &_item {
      display: flex;
      padding: 20px 25px;
      border: 2px solid #d6d6d6;
      gap: 15px;
      border-radius: 5px;

      .right-block {
        display: flex;
        flex-direction: column;
      }

      .read-more_btn {
        background-color: var(--primary-color);
        color: var(--white-color);
        border-radius: 5px;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        width: max-content;
      }

      img {
        width: 150px;
      }

      .book-name {
        font-size: 18px;
        margin: 0;
      }

      .book-author {
        margin: 0;
        font-size: 14px;
      }

      .book-description {
        flex-grow: 1;
      }
    }
  }
}
</style>
