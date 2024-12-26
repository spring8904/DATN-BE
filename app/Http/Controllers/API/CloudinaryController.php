<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\VideoUploadService;
use App\Traits\UploadToCloudinaryTrait;
use App\Traits\UploadToMuxTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CloudinaryController extends Controller
{
    use UploadToCloudinaryTrait, UploadToMuxTrait;

    protected $videoUploadService;

    public function __construct(VideoUploadService $videoUploadService)
    {
        $this->videoUploadService = $videoUploadService;
    }

    public function upload(Request $request)
    {
        DB::beginTransaction();

        $request->validate([
            'image' => 'required',
        ]);

        $image = $request->file('image');

        $uploadFile =  $this->uploadVideo($image);

        $muxVideoUrl = $this->videoUploadService->uploadVideoToMux($uploadFile['secure_url']);

        DB::commit();

        return response()->json([
            'duration' => $uploadFile['duration'],
            'playback_id' => $muxVideoUrl
        ]);
    }

    public function delete(Request $request)
    {

        $request->validate([
            'public_id' => 'required',
        ]);

        $publicId = $request->public_id;

        $this->deleteImage($publicId);
        return response()->json(['message' => 'File deleted']);
    }

    public function getVideoDuration($assetId)
    {
        try {
            $duration = $this->videoUploadService->getVideoDurationToMux($assetId);

            return $duration;
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra khi lấy thời lượng video, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteVideoFromMux($assetId)
    {
        try {
            $this->videoUploadService->deleteVideoFromMux($assetId);
            return $this->respondNoContent();
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra khi xóa video, vui sách thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
