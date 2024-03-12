<?php

namespace App\Services;

use App\Models\Book;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookService
{
    public function get(array $attributes): JsonResponse
    {
        $perPage = $attributes['per_page'] ?? 10;
        $page = $attributes['page'] ?? 1;
        $order = $attributes['order'] ?? 'asc';

        $authors = Book::with('authors')
            ->orderBy('created_at', $order)
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($authors);
    }

    public function fuzzySearch(array $attributes): array
    {
        $search_key = $attributes['search_key'];
        return DB::select(
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

    public function storeBook(array $attributes): Book|JsonResponse
    {
        $book = new Book();
        $book->title = $attributes['title'];
        $book->ISBN = $attributes['ISBN'] ?? null;
        $book->description = $attributes['description'] ?? null;
        $book->published_at = isset($attributes['published_at']) ? $this->parseDate($attributes['published_at']) : null;
        $book->genre = $attributes['genre'] ?? null;
        $book->language = $attributes['language'] ?? null;
        $book->publisher = $attributes['publisher'] ?? null;
        $book->search_key = $attributes['title'];
        $book->save();

        return $book;
    }

    private function parseDate(string $dateString): ?string
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            return $dateString;
        }

        $date = Carbon::createFromFormat('D M d Y H:i:s e+', $dateString);

        if ($date instanceof Carbon) {
            return $date->toDateString();
        } else {
            return null;
        }
    }

    /**
     * @throws Exception
     */
    public function uploadFile(UploadedFile $file, Book $book): JsonResponse
    {
        try {
            if ($file->isValid()) {
                $path = $file->store('pdfs');
                $book->link = $path;
                $book->save();

                return response()->json(['message' => 'PDF uploaded successfully', 'path' => $path], 201);
            }
        } catch (Exception $e) {
            Log::info('uploadFile: ' . $e->getMessage());
            throw new Exception('file was not uploaded look in logs');
        }
        return response()->json(['message' => 'somethng went wrong'], 500);

    }

    public function uploadCoverImage(UploadedFile $coverImage, Book $book): JsonResponse
    {
        try {
            if ($coverImage->isValid()) {
                $imageName = uniqid('cover_image_') . '.' . $coverImage->getClientOriginalExtension();
                $imagePath = $coverImage->storeAs('public', $imageName);
                $book->cover_image = asset('storage/' . $imageName);
                $book->save();

                return response()->json(['message' => 'Image uploaded successfully', 'path' => $imagePath], 201);
            }
        } catch (Exception $e) {
            Log::info('uploadImage: ' . $e->getMessage());
            throw new Exception('Image was not uploaded look in logs');
        }
        return response()->json(['message' => 'somethng went wrong'], 500);

    }

    public function addAuthors(array $attributes, $book): void
    {
        if (isset($attributes['author_ids']) && count($attributes['author_ids']) > 0) {
            $authorIds = $attributes['author_ids'];
            $book->authors()->attach($authorIds);
            $this->reindexAuthor($book);
            $book->save();
        }
    }

    public function reindexAuthor($book): void
    {
        $searchKey = $book->title;
        foreach ($book->authors as $author) {
            $searchKey .= $author->initials;
        }

        $book->search_key = $searchKey;
    }

    //use dto

    /**
     * @throws Exception
     */
    public function updateBook(array $attributes, int $bookId): Book
    {
        $book = Book::query()
            ->findOrFail($bookId);

        $book->title = $attributes['title'] ?? $book->title;
        $book->ISBN = $attributes['ISBN'] ?? $book->ISBN;
        $book->description = $attributes['description'] ?? $book->description;
        $book->published_at = isset($attributes['published_at']) ? $this->parseDate($attributes['published_at']) : $book->published_at;
        $book->genre = $attributes['genre'] ?? $book->genre;
        $book->language = $attributes['language'] ?? $book->language;
        $book->publisher = $attributes['publisher'] ?? $book->publisher;
        $book->search_key = $attributes['title'] ?? $book->search_key;

        $book->save();

        if ($book instanceof Book) {
            return $book;
        }

        throw new Exception('updating book type mismatch exception');
    }

    public function downloadFile(int $id): BinaryFileResponse|JsonResponse
    {
        $book = Book::query()
            ->find($id);

        if ($book instanceof Book) {
            $filePath = config('proj_env.STORAGE_PATH') . $book->link;

            if (!file_exists($filePath)) {
                abort(404, 'File not found.');
            }

            $fileSize = filesize($filePath);

            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION); // Get the file extension

            return response()->download($filePath, $book->title . '.' . $fileExtension, ['Content-Length' => $fileSize]);
        }

        return response()->json(['error' => 'Book fetching exception'], 404);
    }

    public function update(array $attributes, UploadedFile|null $file, int $id, UploadedFile|null $coverImage)
    {
        return DB::transaction(function () use ($attributes, $file, $id, $coverImage) {

            $book = $this->updateBook($attributes, $id);
            $this->uploadBookFileAndRmOld($file, $book);
            $this->uploadBookCoverImgAndRmOld($coverImage, $book);
            $this->addAuthors($attributes, $book);

            return response()->json(['message' => 'Book stored successfully', 'book' => $book], 201);
        });
    }

    public function store(array $attributes, UploadedFile|null $file, UploadedFile|null $coverImg)
    {
        return DB::transaction(function () use ($attributes, $file, $coverImg) {
            $book = $this->storeBook($attributes);
            if ($file) {
                $this->uploadFile($file, $book);
            }
            if ($coverImg) {
                $this->uploadBookCoverImgAndRmOld($coverImg, $book);
            }
            $this->addAuthors($attributes, $book);

            return response()->json(['message' => 'Book stored successfully', 'book' => $book]);
        });
    }

    public function delete(int $id): void
    {
        $book = Book::query()->findOrFail($id);

        $fileLink = $book->link;

        $book->delete();

        // Remove the associated file from storage
        if ($fileLink) {
            Storage::delete($fileLink);
        }
    }

    function uploadBookFileAndRmOld(?UploadedFile $file, Book $book): void
    {
        if ($file?->isValid()) {

            if ($book->link) {
                Log::info(['old file was deleted ' . Storage::delete($book->link)]);
            }

            $this->uploadFile($file, $book);
        } else {
            Log::info(['Book service update:pdf was not uploaded' => $file?->isValid()]);
        }
    }

    /**
     * @throws Exception
     */
    function uploadBookCoverImgAndRmOld(?UploadedFile $coverImage, Book $book): void
    {
        if ($coverImage?->isValid()) {

            if ($book->cover_image) {
                Log::info(['old cover_image was deleted ' . Storage::delete($book->cover_image)]);
            }

            $this->uploadCoverImage($coverImage, $book);
        } else {
            Log::info(['Book service update:image was not uploaded' => $coverImage?->isValid()]);
        }
    }
}
