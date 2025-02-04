<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseSubmittedNotification extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    public $course;

    /**
     * Create a new notification instance.
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Yêu cầu kiểm duyệt khóa học mới')
            ->line('Khóa học "' . $this->course->name . '" đã được gửi yêu cầu kiểm duyệt.')
            ->action('Xem chi tiết', url('test' . $this->course->id))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'course_submitted',
            'course_id' => $this->course->id,
            'course_name' => $this->course->name,
            'course_slug' => $this->course->slug,
            'message' => 'Khóa học "' . $this->course->name . '" đã được gửi yêu cầu kiểm duyệt.',
            'url' => 'test' . $this->course->id,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @return array<string, mixed>
     */

    public function toBroadcast(object $notifiable): array
    {
        return [
            'course_id' => $this->course->id,
            'slug' => $this->course->slug,
            'course_name' => $this->course->name,
            'message' => 'Có khóa học mới cần kiểm duyệt',
            'url' => 'test' . $this->course->id,
        ];
    }
}
