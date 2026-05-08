<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function upload(UploadedFile $file, string $folder, string $disk = 'public'): string
    {
        $name = Str::uuid().'.'.$file->getClientOriginalExtension();
        return $file->storeAs($folder, $name, $disk);
    }

    public function replace(?string $existing, UploadedFile $file, string $folder, string $disk = 'public'): string
    {
        if ($existing) {
            $this->delete($existing, $disk);
        }
        return $this->upload($file, $folder, $disk);
    }

    public function delete(?string $path, string $disk = 'public'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
