<?php

namespace App\Services;

use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthorService
{

    public function paginate(array $attributes): JsonResponse
    {
        $perPage = $attributes['per_page'] ?? 10;
        $page = $attributes['page'] ?? 1;
        $order = $attributes['order'] ?? 'asc';


        $authors = Author::with('books')
            ->orderBy('created_at', $order)
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($authors);
    }

    public function fuzzySearch(array $attributes): array
    {
        $initials = $attributes['initials'];

        return DB::select(
            "select *
                   from authors
                   where initials % :initials;",
            array('initials' => $initials)
        );
    }

    public function store(array $attributes, UploadedFile|null $file): JsonResponse
    {
        try {
            return DB::transaction(function () use ($attributes, $file) {
                $author = new Author();
                $author->initials = $attributes['initials'] ?? null;
                $author->date_of_birth = isset($attributes['date_of_birth']) ? $this->parseDate($attributes['date_of_birth']) : null;
                $author->nationality = $attributes['nationality'] ?? null;
                $author->biography = $attributes['biography'] ?? null;
                $author->save();

                if (isset($attributes['book_ids']) && count($attributes['book_ids']) > 0) {
                    self::attachBooks($author, $attributes['book_ids']);
                }

                $this->uploadFile($file, $author);

                return response()->json(['author' => $author], 201);
            });

        } catch (\Exception $e) {
            Log::info('storing authors ' . $e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
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

    public static function attachBooks($author, array $bookIds): void
    {
        if (count($bookIds) > 0) {
            $author->books()->attach($bookIds);
        }
    }

    public function update(array $attributes, UploadedFile|null $file, int $id): JsonResponse
    {
        $author = Author::query()
            ->findOrFail($id);

        if (isset($attributes['book_ids']) && count($attributes['book_ids'])) {
            self::attachBooks($author, $attributes['book_ids']);
        }

        unset($attributes['book_ids']);
        unset($attributes['author_id']);

        foreach ($attributes as $key => $value) {
            if (isset($value)) {
                $author->$key = $value;
            }
        }

        if (isset($author->date_of_birth)) {
            $author->date_of_birth = $this->parseDate($author->date_of_birth);
        }

        $author->save();

        $this->uploadFile($file, $author);

        return response()->json([
            'message' => 'Author updated successfully',
            'author' => $author
        ]);
    }

    public function uploadFile(UploadedFile|null $file, $author): JsonResponse
    {
        if ($file?->isValid()) {

            if ($author->photo_link) {
                Log::info('author log', ['old author image was deleted: ' . Storage::delete($author->photo_link)]);
            }

            $path = $file->store('imgs');
            $author->photo_link = config('proj_env.STORAGE_PATH') . $path;
            $author->save();

            return response()->json(['message' => 'img uploaded successfully', 'path' => $path], 201);
        }

        Log::info('is valid file:' . $file?->isValid());
        return response()->json(['error' => 'Invalid file'], 400);
    }

    public function getAll(): JsonResponse
    {

        $authors = Author::query()->select('id', 'initials')
            ->get();

        return response()->json($authors);
    }
}
