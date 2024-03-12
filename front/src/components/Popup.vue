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
  bottom: 15%;
  right: 2%;
  padding: 10px;
  background-color: #f16c7c;
  color: white;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(255, 0, 0, 0.5); /* Initial shadow color */
  display: flex;
  align-items: center;
  z-index: 9999; /* Adjust the z-index value as needed */
  animation: rainbow-shadow 2s infinite alternate; /* Wave animation */
}

.custom-message-close {
  margin-left: 10px;
  cursor: pointer;
}

@keyframes rainbow-shadow {
  0% {
    box-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
  }
  20% {
    box-shadow: 0 0 10px rgba(255, 255, 0, 0.5);
  }
  40% {
    box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
  }
  60% {
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
  }
  80% {
    box-shadow: 0 0 20px rgba(0, 0, 255, 0.5);
  }
  100% {
    box-shadow: 0 0 10px rgba(255, 0, 255, 0.5);
  }
}

</style>
