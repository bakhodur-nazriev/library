<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequests\BookSearchRequest;
use App\Http\Requests\BookRequests\BookStoreRequest;
use App\Http\Requests\BookRequests\BookUpdateRequest;
use App\Jobs\IndexBookSearchKeysJob;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $authors = Book::with('authors')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($authors);
    }

    //todo optimize search
    public function search(BookSearchRequest $request, string $search_key): array
    {
          return  DB::select(
                "select
                        tab.title,
                        tab.description,
                        tab.genre,
                        tab.language,
                        tab.publisher
	                from (select
	                        title,
	                        description,
	                        genre,
	                        language,
	                        publisher,
                            similarity(search_key, :search_key) as sim
                        from books
                        where search_key % :search_key) as tab;",
                array('search_key' => $search_key)
        );
    }

    public function store(BookStoreRequest $request): JsonResponse
    {
        try {
            $book = new Book();
            $book->title = $request->input('title');
            $book->ISBN = $request->input('ISBN');
            $book->description = $request->input('description');
            $book->published_at = $request->input('published_at');
            $book->genre = $request->input('genre');
            $book->language = $request->input('language');
            $book->publisher = $request->input('publisher');

            //author
            if ($request->file('pdf')?->isValid()) {
                $path = $request->file('pdf')->store('pdfs');
                $book->link = config('proj_env.STORAGE_PATH') . $path;
            }

            $book->save();

            if ($request->filled('author_ids')) {
                $authorIds = $request->input('author_ids');

                if (is_array($authorIds)) {
                    $book->authors()->attach($authorIds);
//                    dispatch(new IndexBookSearchKeysJob($book));

                    $searchKey = $book->title;
                    foreach ($book->authors as $author) {
                        $searchKey .= $author->initials;
                    }
                    $book->search_key = $searchKey;
                    $book->save();
                }
            }

            return response()->json(['message' => 'PDF uploaded successfully']);
        } catch (\Exception $e) {
            Log::info('BookCOntroller store ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);

        }
    }

    public function showById(int $id): JsonResponse
    {
        return response()->json(Book::query()->find($id));
    }

    public function update(BookUpdateRequest $request, int $id): JsonResponse
    {
        $book = Book::query()
            ->findOrFail($id);

        foreach ($request->all() as $key => $value) {
            if ($request->has($key)) {
                $book->$key = $value;
            }
        }

        $book->save();

        if ($request->filled('author_ids')) {
            $authorIds = $request->input('author_ids');

            if (is_array($authorIds)) {
                $book->authors()->attach($authorIds);
//                    dispatch(new IndexBookSearchKeysJob($book));

                $searchKey = $book->title;
                foreach ($book->authors as $author) {
                    $searchKey .= $author->initials;
                }
                $book->search_key = $searchKey;
                $book->save();
            }
        }

        return response()->json(['message' => 'Book updated successfully', 'book' => $book]);
    }


    public function destroy(int $id): JsonResponse
    {
        Book::query()
            ->find($id)
            ->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }

    public function uploadPdf(Request $request): JsonResponse
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($request->file('pdf')->isValid()) {
            $path = $request->file('pdf')->store('pdfs');

            $book = new Book();
            $book->title = $request->input('title');
            $book->author = $request->input('author');
            $book->link = $path;
            $book->save();

            return response()->json(['message' => 'PDF uploaded successfully', 'path' => $path], 201);
        } else {
            return response()->json(['error' => 'Invalid file'], 400);
        }
    }

    public function download(int $id): string
    {
        $book = Book::query()
            ->find($id);
        return $book->link;
    }

}
