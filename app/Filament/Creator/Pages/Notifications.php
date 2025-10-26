<?php

namespace App\Filament\Creator\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Notifications extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Notifications';
    protected static ?string $navigationGroup = 'Profile';
    protected static string $view = 'filament.creator.pages.notifications';
    
    public function getViewData(): array
    {
        $user = Auth::user();
        
        // Get course-related notifications
        $courseNotifications = $user->notifications()
            ->where('type', 'LIKE', '%Course%')
            ->latest()
            ->limit(50)
            ->get();
        
        // Group by type
        $grouped = [
            'approvals' => [],
            'rejections' => [],
            'edits_requested' => [],
            'published' => [],
            'enrollments' => [],
            'reviews' => [],
            'platform' => [],
        ];
        
        foreach ($courseNotifications as $notification) {
            $data = $notification->data;
            
            if (isset($data['type'])) {
                switch ($data['type']) {
                    case 'course_approved':
                        $grouped['approvals'][] = $notification;
                        break;
                    case 'course_rejected':
                        $grouped['rejections'][] = $notification;
                        break;
                    case 'course_edits_requested':
                        $grouped['edits_requested'][] = $notification;
                        break;
                    case 'course_published':
                        $grouped['published'][] = $notification;
                        break;
                    case 'new_enrollment':
                        $grouped['enrollments'][] = $notification;
                        break;
                    case 'new_review':
                        $grouped['reviews'][] = $notification;
                        break;
                    default:
                        $grouped['platform'][] = $notification;
                }
            }
        }
        
        return [
            'notifications' => $courseNotifications,
            'grouped' => $grouped,
            'unreadCount' => $user->unreadNotifications()->count(),
        ];
    }
    
    public function markAsRead($notificationId): void
    {
        Auth::user()->notifications()->find($notificationId)?->markAsRead();
        
        $this->dispatch('notification-read');
    }
    
    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        \Filament\Notifications\Notification::make()
            ->title('All notifications marked as read')
            ->success()
            ->send();
    }
}
