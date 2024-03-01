<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthorService
{

    public function get(array $attributes): JsonResponse
    {
        $perPage = $attributes['per_page'];
        $page = $attributes['page'];

        $authors = Author::with('books')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($authors);
    }

    public function fuzzySearch(array $attributes): array
    {
        $initials = $attributes['attributes'];

        return DB::select(
            "select
                        tab.initials,
	                from (select
	                        initials
                            similarity(initials, :initials) as sim
                        from authors
                        where initials % :initials) as tab;",
            array('initials' => $initials)
        );
    }

    public function store(array $attributes): JsonResponse
    {
        try {
            return DB::transaction(function () use ($attributes) {
                $author = new Author();
                $author->initials = $attributes['initials'];
                $author->date_of_birth = $attributes['date_of_birth'];
                $author->nationality = $attributes['nationality'];
                $author->biography = $attributes['biography'];
                $author->save();

                BookService::attachAuthors($author, $attributes['book_ids']);

                return response()->json(['author' => $author], 201);
            });

        } catch (\Exception $e) {
            Log::info('storing authors ' . $e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(array $attributes, int $id): JsonResponse
    {
        $author = Author::query()
            ->findOrFail($id);

        $author->fill($attributes);
        $author->save();

        BookService::attachAuthors($author, $attributes['book_ids']);

        return response()->json(['message' => 'Author updated successfully', 'author' => $author]);
    }
}
