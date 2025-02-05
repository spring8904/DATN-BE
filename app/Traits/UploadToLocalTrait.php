<?php

namespace App\Traits;

use F9Web\ApiResponseHelpers;
use Illuminate\Support\Facades\Storage;

trait UploadToLocalTrait
{
    use LoggableTrait, ApiResponseHelpers;

    public function uploadToLocal($file, $directory = 'uploads')
    {
        try {
            if (!$file->isValid()) {
                return $this->respondBadRequest('Invalid file');
            }

            $file = Storage::put($directory, $file);

            return $file;
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Failed to upload file');
        }
    }

    public function deleteFromLocal($filePath, $directory = 'uploads')
    {
        try {
            if (Storage::exists($filePath)) {
                return Storage::delete($filePath);
            }

            return $this->respondNotFound('File not found');
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Failed to delete file');
        }
    }
}
