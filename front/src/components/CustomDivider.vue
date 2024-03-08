<script setup>
const props = defineProps(['title']);
</script>

<template>
  <p class="title-section">{{ props.title }}</p>
  <div class="title-bar">
    <ul>
      <li></li>
      <li></li>
    </ul>
  </div>
</template>

<style scoped lang="scss">
.title-bar {
  background-color: var(--color-primary);
  height: 2px;
  max-width: 100%;
  position: relative;
  width: 400px;
  color: var(--color-primary);
  margin: 10px auto 30px;

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
      background-color: var(--color-primary);
      transform: rotate(45deg)
    }
  }
}

.title-section {
  text-align: center;
  margin-top: 0;
  margin-bottom: 20px;
  font-size: 32px;
  font-weight: bold;
  color: #5b5b5b;
}
</style>