<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

trait UploadToLocalTrait
{
    use LoggableTrait;

    public function uploadToLocal($file, $directory = 'uploads')
    {
        try {
            if (!$file->isValid()) {
                return response()->json([
                    'message' => 'Invalid file',
                ], Response::HTTP_BAD_REQUEST);
            }

            $file = Storage::put($directory, $file);

            return $file;
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteFromLocal($filePath, $directory = 'uploads')
    {
        try {
            if (Storage::exists($filePath)) {
                return Storage::delete($filePath);
            }

            return response()->json([
                'message' => 'File not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
