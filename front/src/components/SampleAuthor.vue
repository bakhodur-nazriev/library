<script setup>
const props = defineProps(['authors']);
</script>

<template>
  <li
      v-for="(author, i) in authors"
      :key="i"
      class="authors-list_item"
  >
    <img
        class="author-image"
        v-if="author.photo_link"
        :src="author.photo_link"
        :alt="author.name"
    >
    <div v-else class="authors-cover-block">
      <span>{{ $t('label.author_empty_cover') }}</span>
    </div>
    <div class="right-block">
      <div>
        <h3 v-if="author.initials" class="author-name">{{ author.initials }}</h3>
        <p v-if="author.nationality" class="author-nationality">{{ author.nationality }}</p>
        <p v-if="author.date_of_birth" class="author-birth">{{ author.date_of_birth }}</p>
        <p v-if="author.biography" class="author-biography">{{ author.biography }}</p>
      </div>
      <div>
        <button
            type="button"
            class="read-more_btn"
        >
          {{ $t('buttons.read_more') }}
        </button>
      </div>
    </div>
  </li>
</template>

<style scoped lang="scss">
.authors-list_item {
  display: flex;
  padding: 20px 25px;
  border: 2px solid #d6d6d6;
  gap: 15px;
  border-radius: 5px;
  min-height: 286px;
  max-height: 286px;
  max-width: 500px;

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
    justify-content: space-between;
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

  .author-image {
    width: 150px;
    border-radius: 5px;
  }

  .author {
    &-name {
      font-size: 16px;
      margin: 0;
    }

    &-nationality {
      font-size: 14px;
      margin: 6px 0;
    }

    &-birth {
      font-size: 14px;
      margin: 6px 0;
    }

    &-biography {
      font-size: 14px;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 6;
      overflow: hidden;
      margin: 6px 0;
    }
  }
}

@media (max-width: 768px) {
  .authors-list_item {
    display: flex;
    flex-direction: column;
    max-height: 100%;
  }
}
</style>