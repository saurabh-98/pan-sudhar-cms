<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatAttachmentService
{
    /**
     * Upload Attachment
     */
    public function upload(
        UploadedFile $file,
        string $folder = 'chat'
    ): array {

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $filename = Str::uuid().'.'.$extension;

        $path = $file->storeAs(
            $folder,
            $filename,
            'public'
        );

        return [

            'path' => $path,

            'name' => $file->getClientOriginalName(),

            'type' => $file->getMimeType(),

            'size' => $file->getSize(),

            'extension' => $extension,

            'url' => Storage::disk('public')->url($path),

        ];
    }

    /**
     * Delete Attachment
     */
    public function delete(
        ?string $path
    ): bool {

        if (
            empty($path) ||
            !Storage::disk('public')->exists($path)
        ) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    /**
     * Check Attachment Exists
     */
    public function exists(
        string $path
    ): bool {

        return Storage::disk('public')->exists($path);
    }

    /**
     * Get Attachment URL
     */
    public function url(
        ?string $path
    ): ?string {

        if (!$path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Download Attachment
     */
    public function download(
        string $path
    )
    {
        abort_unless(
            Storage::disk('public')->exists($path),
            404
        );

        return Storage::disk('public')
            ->download($path);
    }

    /**
     * Preview Attachment
     */
    public function preview(
        string $path
    )
    {
        abort_unless(
            Storage::disk('public')->exists($path),
            404
        );

        return response()->file(
            Storage::disk('public')->path($path)
        );
    }
}