<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Documents\DocumentRequest;
use App\Models\Document;
use App\Traits\LoggableTrait;
use App\Traits\UploadToLocalTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    use LoggableTrait, UploadToLocalTrait;

    const DOCUMENT_LESSON = 'documents/lessons';
    public function index(string $id)
    {
        try {
            $documents = Document::query()
            ->whereHas('lessons', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->latest('id')->get();

            return response()->json([
                'message' => 'Danh sách tài liệu',
                'status' => true,
                'document' => $documents
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logError($e);
            return $this->respondServerError('Không thể tải danh sách tài liệu, vui lòng thử lại');
        }
    }
    public function show(string $id)
    {
        try {
            $document = Document::findOrFail($id);

            return response()->json([
                'message' => 'Chi tiết tài liệu',
                'status' => true,
                'document' => $document
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondNotFound('Tài liệu không tồn tại');
        }
    }

    public function store(DocumentRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('document_file')) {
                $documentFile = $request->file('document_file');
                $data['file_path'] = $this->uploadToLocal($documentFile, self::DOCUMENT_LESSON);
                $data['file_type'] = 'upload';
            } elseif (!empty($request->document_url)) {
                $data['file_path'] = $request->document_url;
                $data['file_type'] = 'url';
            }

            $data['title'] = !empty($data['title']) ? $data['title'] : Str::uuid();

            $document = Document::query()->create($data);

            return $this->respondCreated(['Tài liệu đã được tạo thành công', $document]);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }
    public function update(DocumentRequest $request, string $id)
    {
        try {
            $document = Document::find($id);

            if (!$document) {
                return $this->respondNotFound('Tài liệu không tồn tại');
            }

            $data = $request->validated();

            if ($request->hasFile('document_file')) {
                $documentFile = $request->file('document_file');
                $data['file_path'] = $this->uploadToLocal($documentFile, self::DOCUMENT_LESSON);
                $data['file_type'] = 'upload';
            } elseif (!empty($request->document_url)) {
                $data['file_path'] = $request->document_url;
                $data['file_type'] = 'url';
            }

            $data['title'] = !empty($data['title']) ? $data['title'] : Str::uuid();

            $document =  $document->update($data);

            return response()->json([
                'message' => 'Tài liệu đã được cập nhật thành công',
                'status' => true,
                'document' => $document
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logError($e);
            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }
    public function destroy(string $id)
    {
        try {
            $document = Document::find($id);

            if (!$document) {
                return $this->respondNotFound('Tài liệu không tồn tại');
            }

            if ($document->file_type === 'upload' && Storage::exists($document->file_path)) {
                $this->deleteFromLocal($document->file_path, self::DOCUMENT_LESSON);
            }

            $document->delete();

            return $this->respondNoContent();
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }
}
