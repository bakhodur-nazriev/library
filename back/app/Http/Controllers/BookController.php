<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(): JsonResponse
    {
        $books = Book::all();
        return response()->json($books);
    }

    public function search(string $search_key): array
    {
          return  DB::select(
                "select
                        tab.author
	                from (select
	                        author,
                            similarity(search_key, :search_key) as sim
                        from books
                        where search_key % :search_key) as tab;",
                array('search_key' => $search_key)
        );
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'author' => 'required|string',
            ]);

            $book = new Book();
            $book->title = $request->input('title');
            $book->author = $request->input('author');
            $book->ISBN = $request->input('ISBN');
            $book->description = $request->input('description');
            $book->published_at = $request->input('published_at');
            $book->genre = $request->input('genre');
            $book->language = $request->input('language');
            $book->publisher = $request->input('publisher');

            $book->search_key = $book->title . $book->author;

            $book->save();


            //
            $request->validate([
                'pdf' => 'required|file|mimes:pdf|max:2048',
            ]);

            if ($request->file('pdf')->isValid()) {
                $path = $request->file('pdf')->store('pdfs');
                $book->link = $path;
                $book->save();

                return response()->json(['message' => 'PDF uploaded successfully', 'path' => $path], 201);
            } else {
                return response()->json(['error' => 'Invalid file'], 400);
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['error' => 'look logs'], 500);

        }
    }

    public function showById(int $id): JsonResponse
    {
        return response()->json(Book::query()->find($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $book = Book::query()
            ->findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'string',
            'author' => 'string',
            'description' => 'string',
            'ISBN' => 'string',
            'published_at' => 'date',
            'genre' => 'string',
            'language' => 'string',
            'pages' => 'nullable|integer',
            'publisher' => 'string',
        ]);

        foreach ($validatedData as $key => $value) {
            if ($request->has($key)) {
                $book->$key = $value;
            }
        }

        //reindex
        $book->search_key = $book->title . $book->author;
        //
        $book->save();

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
        return config('proj_env.STORAGE_PATH'). $book->link;
    }

}
