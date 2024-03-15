<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookSearchKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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
            $skObj = new BookSearchKey();

            Log::info(['qweqwe' => $this->modelTypeName() . '_id']);

            $skObj->{$this->modelTypeName() . '_id'} = $this->model->id;
            $skObj->{$this->modelTypeName() . '_search_key'} = $searchKeyCombination;
            $skObj->save();
        }

    }

    private function generateConcatenatedArray(): array
    {
        $inputArray = $this->searchKeys();
        $concatenatedArray = [];
        $currentString = '';
        foreach ($inputArray as $element) {
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

        BookSearchKey::query()
            ->where($foreignKeyColumnName, $this->model->id)
            ->delete();
    }

}
