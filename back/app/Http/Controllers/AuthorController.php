<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequests\AuthorStoreRequest;
use App\Http\Requests\AuthorRequests\AuthorUpdateRequest;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        // Query for the paginated data
        $authors = Author::with('books')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($authors);
    }

    //todo optimize search
    public function search(string $initials): array
    {
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

    public function store(AuthorStoreRequest $request): JsonResponse
    {
        try {
            $author = new Author();
            $author->initials = $request->input('initials');
            $author->date_of_birth = $request->input('date_of_birth');
            $author->nationality = $request->input('nationality');
            $author->biography = $request->input('biography');
            $author->save();

            $author->books()->attach($request->input('book_ids'));

            return response()->json(['author' => $author], 201);
        } catch (\Exception $e) {
            Log::info($e->getMessage());

            return response()->json(['error' => 'look logs'], 500);
        }
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
        $author = Author::query()
            ->findOrFail($id);

        $validatedData = $request->validate([
            'initials' => 'string',
            'date_of_birth' => 'date',
            'book_ids' => 'array',
        ]);

        $author->fill($validatedData);
        $author->save();

        if ($request->has('book_ids')) {
            $author->books()->sync($request->input('book_ids'));
        }

        return response()->json(['message' => 'Author updated successfully', 'author' => $author]);
    }

    public function destroy(int $id): JsonResponse
    {
        Author::query()
            ->find($id)
            ->delete();

        return response()->json(['message' => 'Author deleted successfully']);
    }

}