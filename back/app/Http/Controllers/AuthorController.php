<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequests\AuthorStoreRequest;
use App\Http\Requests\AuthorRequests\AuthorUpdateRequest;
use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

    public function __construct(private readonly AuthorService $authorService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->authorService->get($request->all());
    }

    public function search(string $initials): array
    {
        $attributes['initials'] = $initials;
        return $this->authorService->fuzzySearch($attributes);
    }

    public function store(AuthorStoreRequest $request): JsonResponse
    {
        return $this->authorService->store($request->all());
    }

    public function showById(int $id): JsonResponse
    {
        return response()->json(
            Author::query()
                ->with('books')
                ->find($id)
        );
    }

    public function update(AuthorUpdateRequest $request, int $id): JsonResponse
    {
        return $this->authorService->update($request->all(), $id);
    }

    public function destroy(int $id): JsonResponse
    {
        Author::query()
            ->find($id)
            ->delete();

        return response()->json(['message' => 'Author deleted successfully']);
    }

}
