<script setup>
const props = defineProps(['books']);
import {ref} from 'vue';
// import pdfjs from '/public/lib/build/pdf.mjs'

// const isPdfModalOpen = ref(false);
// const pdfCanvasRef = ref(null);
// let pdfDocument = null;
// let pdfPage = null;
//
// const openPdf = async (pdfFile) => {
//   try {
//     // Открытие PDF-файла
//     const loadingTask = pdfjs.getDocument(pdfFile);
//     const pdf = await loadingTask.promise;
//
//     // Отображение первой страницы
//     const pageNumber = 1;
//     pdfDocument = pdf;
//     pdfPage = await pdf.getPage(pageNumber);
//
//     // Отображение страницы на холсте
//     renderPage();
//
//     // Открываем модальное окно
//     isPdfModalOpen.value = true;
//   } catch (error) {
//     console.error('Error opening PDF:', error);
//   }
// };
//
// const renderPage = async () => {
//   const canvas = pdfCanvasRef.value;
//   const context = canvas.getContext('2d');
//
//   const viewport = pdfPage.getViewport({scale: 1.5});
//   canvas.width = viewport.width;
//   canvas.height = viewport.height;
//
//   const renderContext = {
//     canvasContext: context,
//     viewport: viewport,
//   };
//
//   await pdfPage.render(renderContext);
// };
//
// const closePdfModal = () => {
//   // Закрываем модальное окно и очищаем состояние PDF
//   isPdfModalOpen.value = false;
//   pdfDocument = null;
//   pdfPage = null;
// };
</script>

<template>
  <ul class="books-list">
    <li v-for="(book, i) in books" :key="i" class="books-list_item">
      <img :src="book.cover_image" :alt="book.name" v-if="book.cover_image">
      <div v-else class="book-cover-block">
        <span>{{ $t('label.book_empty_cover') }}</span>
      </div>
      <div class="right-block">
        <h3 class="book-name">{{ book.title }}</h3>
        <h4 class="book-author">{{ $t('label.authors') }}:
          <span v-for="(author, index) in book.authors" :key="index">
            {{ author.initials }}{{ index < book.authors.length - 1 ? ', ' : '' }}
          </span>
        </h4>

        <p class="book-description">{{ book.description }}</p>

        <button
            type="button"
            class="read-more_btn"

        >
          {{ $t('buttons.read_more') }}
        </button>
      </div>
    </li>

<!--    <el-dialog :visible.sync="isPdfModalOpen" width="80%">-->
<!--      <div>-->
<!--        <canvas ref="pdfCanvas"></canvas>-->
<!--      </div>-->
<!--      <span slot="footer">-->
<!--        <el-button @click="closePdfModal">{{ $t('buttons.close') }}</el-button>-->
<!--      </span>-->
<!--    </el-dialog>-->
  </ul>
</template>

<style scoped lang="scss">
.books-list {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
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
    max-width: 410px;
    transition: all 0.3s ease 0s;

    &:hover {
      border: 2px solid var(--color-active-link);
    }

    .right-block {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .read-more_btn {
      background-color: var(--color-primary);
      color: var(--color-white);
      border-radius: 5px;
      border: none;
      padding: 10px 15px;
      cursor: pointer;
      width: max-content;
      font-family: inherit;
    }

    img {
      width: 150px;
    }

    .book-name {
      font-size: 16px;
      margin: 0;
    }

    .book-author {
      margin: 0;
      font-size: 14px;

      span {
        font-weight: normal;
      }
    }

    .book-description {
      flex-grow: 1;
    }

    .book-cover-block {
      background: rgba(0, 0, 0, 0.8);
      min-width: 152px;
      max-width: 152px;
      height: 243px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 5px;
      color: var(--color-white);
    }
  }
}
</style>