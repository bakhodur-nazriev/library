<script setup>

const props = defineProps(['message']);
const emit = defineEmits(['close'])

const closeMessage = () => {
  emit('close');
}
</script>

<template>
  <div class="custom-message">
    {{ props.message }}
    <span @click="closeMessage" class="custom-message-close">×</span>
  </div>
</template>

<style scoped>
.custom-message {
  position: fixed;
  top: 10px;
  right: 10px;
  padding: 10px;
  background-color: #4caf50;
  color: white;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
}

.custom-message-close {
  margin-left: 10px;
  cursor: pointer;
}
</style>
