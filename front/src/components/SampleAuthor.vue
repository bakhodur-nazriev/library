<script setup>
const props = defineProps(['authors']);
</script>

<template>
  <ul class="authors-list">
    <li
        v-for="(author, i) in authors"
        :key="i"
        class="authors-list_item"
    >
      <img v-if="author.link" :src="author.link" :alt="author.name">
      <div v-else class="authors-cover-block">
        <span>{{ $t('label.author_empty_cover') }}</span>
      </div>
      <div class="right-block">
        <h3 class="book-name">{{ author.initials }}</h3>
        <p>{{ author.nationality }}</p>
        <p>{{ author.date_of_birth }}</p>
        <p>{{ author.biography }}</p>
        <button
            type="button"
            class="read-more_btn"
        >
          {{ $t('buttons.read_more') }}
        </button>
      </div>
    </li>
  </ul>
</template>

<style scoped lang="scss">
.authors-list {
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

    .authors-cover-block {
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

    .right-block {
      display: flex;
      flex-direction: column;
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
    }

    .book-description {
      flex-grow: 1;
    }
  }
}
</style>