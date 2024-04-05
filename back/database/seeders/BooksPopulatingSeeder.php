<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Services\SearchKeysService;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\Tcpdf\Fpdi;
use Spatie\PdfToImage\Exceptions\PageDoesNotExist;
use Spatie\PdfToImage\Exceptions\PdfDoesNotExist;
use Spatie\PdfToImage\Pdf;

class BooksPopulatingSeeder extends Seeder
{
    /**
     * @throws PdfParserException
     * @throws PageDoesNotExist
     * @throws PdfDoesNotExist
     * @throws Exception
     */
    public function run(): void
    {
        $directory = __DIR__ . '/sources/books/some_genre';
        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                $pageCount = $this->getPageCount($directory);
                $bookTitle = pathinfo($file, PATHINFO_FILENAME);
                $genre = basename(dirname($directory . '/' . $file));

                $pdf = new Pdf($directory . '/' . $file);
                foreach (range(1, $pdf->getNumberOfPages()) as $pageNumber) {

                    $bookCoverImagePath = storage_path('app/books_cover_images/' . "$bookTitle" . '_ci_' . $pageNumber . '.jpg');
                    if (!file_exists($bookCoverImagePath)) {
                        Storage::put($bookCoverImagePath, '');
                    }

                    $pdf->setPage($pageNumber)
                        ->saveImage($bookCoverImagePath);

                    break;
                }

                //
                $book = new Book();
                $book->title = $bookTitle;
                $book->pages = $pageCount;
                $book->genre = $genre;
                $book->link =
                $book->save();

                (new SearchKeysService($book))->update();


                $uploadedFileBook = new UploadedFile(
                    $directory . '/' . $file,
                    "$bookTitle" . '_link_' . rand(44, 9999),
                    'application/pdf',
                    null,
                    true
                );

                $this->uploadFile($uploadedFileBook, $book);

                //
                $uploadedFileCI = new UploadedFile(
                    $bookCoverImagePath,
                    "$bookTitle" . '_ci_' . $pageNumber . '.jpg',
                    'image/jpeg',
                    null,
                    true
                );

                $this->uploadCoverImage($uploadedFileCI, $book);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function uploadCoverImage(UploadedFile $coverImage, Book $book): JsonResponse
    {
        try {
            if ($coverImage->isValid()) {
                $imageName = $book->title . '_'. rand(1000, 1000000) . '_cover_image_' . '.' . $coverImage->getClientOriginalExtension();
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

    /**
     * @throws PdfParserException
     */
    private function getPageCount($directory): int
    {
        $pdf = new Fpdi();
        $files = scandir($directory);
        $totalPageCount = 0;
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $pdf->setSourceFile($directory . '/' . $file);
            $totalPageCount += $pdf->setSourceFile($directory . '/' . $file);
        }

        return $totalPageCount;
    }

    /**
     * @throws Exception
     */
    public function uploadFile(UploadedFile $file, Book $book): void
    {
        try {
            if ($file->isValid()) {
                $path = $file->store('pdfs');
                $book->link = $path;
                $book->save();
            }
        } catch (Exception $e) {
            Log::info('uploadFile: ' . $e->getMessage());
            throw new Exception('file was not uploaded look in logs');
        }
    }


}
