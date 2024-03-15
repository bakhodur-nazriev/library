<?php

namespace App\Services;

use App\Models\Author;
use App\Models\AuthorSearchKey;
use App\Models\Book;
use App\Models\BookSearchKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SearchKeysService
{

    public function __construct(private readonly Model $model)
    {
    }

    public function update(): void
    {
        $this->removeOldKeys();
        $this->store();
    }

    public function search()
    {
    }

    public function store(): void
    {
        foreach ($this->generateConcatenatedArray() as $searchKeyCombination) {
            $skObj = $this->searchKeyModel();
            $skObj->{$this->modelTypeName() . '_id'} = $this->model->id;
            $skObj->search_key = Str::lower($searchKeyCombination);
            $skObj->save();
        }

    }

    private function generateConcatenatedArray(): array
    {
        $searchKeys = $this->searchKeys();
        $concatenatedArray = array_slice($searchKeys, 1);
        $currentString = '';
        foreach ($searchKeys as $element) {
            $currentString .= $element;
            $concatenatedArray[] = $currentString;
        }
        return $concatenatedArray;
    }

    private function searchKey(): string
    {
        if ($this->model instanceof Book) {
            return $this->model->title;
        }

        if ($this->model instanceof Author) {
            return $this->model->initials;
        }

        Log::info('searchKey wrong model type');
    }

    private function modelTypeName(): string
    {
        if ($this->model instanceof Book) {
            return 'book';
        }

        if ($this->model instanceof Author) {
            return 'author';
        }
    }

    private function searchKeys(): array
    {
        $searchKey = $this->searchKey();
        $searchKey = str_replace('  ', ' ', $searchKey);

        return explode(' ', $searchKey);
    }

    public function removeOldKeys(): void
    {
        $foreignKeyColumnName = $this->modelTypeName() . '_id';

        $this->searchKeyModel()::query()
            ->where($foreignKeyColumnName, $this->model->id)
            ->delete();
    }

    private function searchKeyModel()
    {
        if ($this->model instanceof Book) {
            return new BookSearchKey();
        }

        if ($this->model instanceof Author) {
            return new AuthorSearchKey();
        }

        Log::info('searchKeyModel wrong model type');
    }

}
