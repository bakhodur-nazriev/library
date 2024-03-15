<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequests\AuthorStoreRequest;
use App\Http\Requests\AuthorRequests\AuthorUpdateRequest;
use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthorController extends Controller
{

    public function __construct(private readonly AuthorService $authorService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->authorService->paginate($request->all());
    }

    public function all(Request $request): JsonResponse
    {
        return $this->authorService->getAll();
    }

    public function search(string $initials): array
    {
        $attributes['initials'] = Str::lower(str_replace(' ', '', $initials));;
        return $this->authorService->fuzzySearch($attributes);
    }

    public function store(AuthorStoreRequest $request): JsonResponse
    {
        return $this->authorService->store($request->all(), $request->file('photo_link'));
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
        return $this->authorService->update($request->all(), $request->file('photo_link'), $id);
    }

    public function destroy(int $id): JsonResponse
    {
        $author = Author::query()
            ->findOrFail($id);
            $author->delete();

        if ($author->photo_link) {
            Log::info('author log', ['old author image was deleted: ' . Storage::delete($author->photo_link)]);
        }

        return response()->json(['message' => 'Author deleted successfully']);
    }

}
