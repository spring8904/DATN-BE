<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use LoggableTrait, ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $count = $request->query('count', 10);

            $notifications = $user->notifications()->latest()->take($count)->get();
            $unreadNotificationsCount = $user->unreadNotifications()->count();

            return $this->respondOk('Danh sách thông báo', [
                'notifications' => $notifications,
                'unread_notifications_count' => $unreadNotificationsCount,
            ]);
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondError('Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    public function getUnreadNotificationsCount()
    {
        try {
            $user = Auth::user();

            $unreadNotificationsCount = $user->unreadNotifications()->count();

            return $this->respondOk('Số thông báo chưa đọc', [
                'unread_notifications_count' => $unreadNotificationsCount,
            ]);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondError('Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    public function markAsRead(string $notificationId, Request $request)
    {
        try {
            $user = Auth::user();
            $notification = $user->notifications()->where('id', $notificationId)->first();

            if ($notification) {
                if ($request->read_at === 'true') {
                    $notification->markAsRead();
                } else {
                    $notification->update(['read_at' => null]);
                }

                return $this->respondOk(
                    $notification->read_at ? 'Đánh dấu đã đọc thành công' : 'Đánh dấu chưa đọc thành công',
                );
            }

            return $this->respondError('Thông báo không tìm thấy');
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondError('Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }
}
