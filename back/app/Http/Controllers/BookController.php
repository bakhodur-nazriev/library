<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequests\BookSearchRequest;
use App\Http\Requests\BookRequests\BookStoreRequest;
use App\Http\Requests\BookRequests\BookUpdateRequest;
use App\Http\Requests\BookRequests\UploadFileRequest;
use App\Models\Author;
use App\Models\Book;
use App\Services\BookService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookController extends Controller
{

    public function __construct(private readonly BookService $bookService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->bookService->get($request->all());
    }

    public function search(BookSearchRequest $request, string $search_key): array
    {
        //todo
        $arguments = $request->all();
        $arguments['search_key'] = $search_key;

        return $this->bookService->fuzzySearch($arguments);
    }

    public function store(BookStoreRequest $request)
    {
        return $this->bookService->store($request->all(), $request->file('file'));
    }

    public function showById(int $id): JsonResponse
    {
        return response()->json(Book::query()->find($id));
    }

    /**
     * @throws Exception
     */
    public function update(BookUpdateRequest $request, int $id): JsonResponse
    {
       return $this->bookService->update($request->all(), $request->file('file'), $id);
    }

    public function detachFromBookAuthor(int $bookId, int $authorId): JsonResponse
    {
        $book =  Book::query()
            ->find($bookId);
        $book->authors()->detach(Author::query()->find($authorId));

        return response()->json(['message' => 'detached', 'book' => $book]);
    }

    public function detachFromAuthorBook(int $authorId, int $bookId): JsonResponse
    {
        $author =  Author::query()
            ->find($authorId);
        $author->books()->detach(Book::query()->find($bookId));

        return response()->json(['message' => 'detached', 'author' => $author]);
    }


    public function destroy(int $id): JsonResponse
    {
        Book::query()
            ->find($id)
            ->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }

    //todo
    public function uploadPdf(UploadFileRequest $request, int $bookId): JsonResponse
    {
        $book = Book::query()
            ->find($bookId);

        if ($book instanceof Book) {
            return $this->bookService->uploadFile($request->file('pdf'), $book);
        }

        return response()->json(['error' => 'Book fetching exception'], 400);
    }

    public function download(int $id): BinaryFileResponse|JsonResponse
    {
       return $this->bookService->downloadFile($id);
    }


}
