<script setup>
const emit = defineEmits(['close']);
const emitClose = () => {
  emit('close');
};
</script>

<template>
  <div
      class="modal-overlay"
      @click="emitClose"
      element-loading-background="rgba(0, 0, 0, 0.7)"
      :element-loading-text="$t('loading')"
  >
    <slot></slot>
  </div>
</template>

<style scoped lang="scss">
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 15;
}
</style>