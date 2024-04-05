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

    private string $baseDirectory = __DIR__ . '/sources/books/УЧЕБНАЯ ЛИТЕРАТУРА';
    private int $count = 300;

    private $progressBar;
    /**
     * @throws Exception
     */
    public function run(): void
    {
         $this->progressBar = $this->command->getOutput()->createProgressBar($this->count);

        try {
            $this->processDirectory($this->baseDirectory);
        } catch (Exception $e) {
            Log::info('book seeder', [$e->getMessage()]);
        }

        $this->progressBar->finish();

        $this->command->info("\nSeeding completed successfully!");
    }

    /**
     * @throws PdfParserException
     * @throws PdfDoesNotExist
     * @throws PageDoesNotExist
     * @throws Exception
     */
    private function processDirectory(string $directory): void
    {
        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $directory . '/' . $file;

            if (is_dir($filePath)) {
                $this->processDirectory($filePath);
            } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {

                $this->progressBar->advance();

                $this->processPdfFile($filePath);
                unlink($filePath);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function processPdfFile(string $filePath): void
    {
        $pageCount = $this->getPageCount($filePath);
        $bookTitle = pathinfo($filePath, PATHINFO_FILENAME);
        $genre = basename($this->baseDirectory);

        try {
            $pdf = new Pdf($filePath);
            foreach (range(1, $pdf->getNumberOfPages()) as $pageNumber) {
                $bookCoverImagePath = storage_path('app/books_cover_images/' . "$bookTitle" . '_ci_' . $pageNumber . '.jpg');
                if (!file_exists($bookCoverImagePath)) {
                    Storage::put($bookCoverImagePath, '');
                }

                $pdf->setPage($pageNumber)
                    ->saveImage($bookCoverImagePath);
                break;
            }
        } catch (Exception $e) {
            Log::info('saveImage', [$e->getMessage()]);
        }


        $book = new Book();
        $book->title = $bookTitle;
        $book->pages = $pageCount;
        $book->genre = $genre;
        $book->save();

        (new SearchKeysService($book))->store();

        $uploadedFileBook = new UploadedFile(
            $filePath,
            "$bookTitle" . '_link_' . rand(44, 9999),
            'application/pdf',
            null,
            true
        );

        $this->uploadFile($uploadedFileBook, $book);

        $uploadedFileCI = new UploadedFile(
            $bookCoverImagePath,
            "$bookTitle" . '_ci_' . $pageNumber . '.jpg',
            'image/jpeg',
            null,
            true
        );
        $this->uploadCoverImage($uploadedFileCI, $book);
    }

    /**
     * @throws Exception
     */
    public function uploadCoverImage(UploadedFile $coverImage, Book $book): JsonResponse
    {
        try {
            if ($coverImage->isValid()) {
                $imageName = $book->title . '_' . rand(1000, 1000000) . '_cover_image_' . '.' . $coverImage->getClientOriginalExtension();
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


    private function getPageCount($file): int
    {
        $totalPageCount = 0;
        try {
            $pdf = new Fpdi();
            $totalPageCount += $pdf->setSourceFile($file);
        } catch (Exception $e) {
            Log::info('getPageCount', [$e->getMessage()]);
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
