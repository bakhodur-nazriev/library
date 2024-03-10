<script setup>
const props = defineProps(['pdfFile']);
</script>

<template>
  <iframe :src="pdfFile" frameborder="0" width="100%" height="100%"></iframe>
</template>

<style scoped lang="scss">

</style>