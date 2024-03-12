<script setup>
const props = defineProps(['message']);
</script>

<template>
  <div class="custom-message">
    <el-alert :title="message" type="error" effect="dark"/>
  </div>
</template>

<style scoped>
.custom-message {
  width: 320px;
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style>
