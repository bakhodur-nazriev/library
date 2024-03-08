<script setup>
import pdfjs from 'pdfjs-dist/build/pdf.mjs';
import {onMounted, ref} from "vue";

const props = defineProps(['pdf-link']);
const pdfDocument = ref(null);
const pdfPage = ref(null);

const loadPdf = async () => {
  try {
    // Загрузка PDF-файла (замените на путь к вашему файлу)
    const pdfUrl = 'path/to/your/pdf/document.pdf';
    const loadingTask = pdfjs.getDocument(pdfUrl);
    const pdf = await loadingTask.promise;

    // Отображение первой страницы
    const pageNumber = 1;
    pdfDocument.value = pdf;
    pdfPage.value = await pdf.getPage(pageNumber);

    // Отображение страницы на холсте
    renderPage();
  } catch (error) {
    console.error('Error loading PDF:', error);
  }
};

const renderPage = async () => {
  const canvas = pdfCanvas.value;
  const context = canvas.getContext('2d');

  const viewport = pdfPage.getViewport({scale: 1.5});
  canvas.width = viewport.width;
  canvas.height = viewport.height;

  const renderContext = {
    canvasContext: context,
    viewport: viewport,
  };

  await pdfPage.render(renderContext);
};

onMounted(() => {
  loadPdf();
})
</script>

<template>
  <canvas ref="pdfCanvas"></canvas>
</template>

<style scoped lang="scss">

</style>