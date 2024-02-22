<script setup>
import LoupeIcon from '../components/icons/LoupeIcon.vue';

const props = defineProps({
  placeholder: {
    type: String,
  }
})
</script>

<template>
  <div class="main__search--block">
    <LoupeIcon class="search__icon"/>
    <input
        class="base__search--input"
        :placeholder="props.placeholder"
    />
  </div>
</template>

<style scoped lang="scss">
.base__search--input {
  border-radius: 10px;
  background-color: var(--bg-grey-color);
  color: var(--color-mine-shaft);
  border: none;
  outline: none;
  font-size: 16px;
  padding: 12px 12px 12px 48px;

  &::placeholder {
    color: var(--color-silver-chalice);
  }
}

.main__search--block {
  position: relative;
  display: flex;
  align-items: center;

  &.full {
    .base__search--input {
      width: 100%;
    }
  }

  .search__icon {
    position: absolute;
    top: 50%;
    left: 16px;
    transform: translateY(-50%);
    display: flex;
  }
}

@media (min-width: 768px) {
  .base__search--input {
    width: 280px;
  }
}

@media (min-width: 992px) {
  .base__search--input {
    width: 300px;
  }
}

@media (min-width: 1200px) {
  .base__search--input {
    width: 300px;
  }
}
</style>
