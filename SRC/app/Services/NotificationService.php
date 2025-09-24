<?php

namespace App\Services;

use App\Models\User;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\Discussion;
use App\Models\ReadingGroup;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send loan due reminder notification
     */
    public function sendLoanDueReminder(Loan $loan)
    {
        $user = $loan->user;
        
        if (!$user->wantsNotification('loan_due') || !$user->email_notifications_enabled) {
            return;
        }

        $daysUntilDue = now()->diffInDays($loan->due_date, false);
        
        if ($daysUntilDue <= 1 && $daysUntilDue >= 0) {
            $title = $daysUntilDue == 0 ? 'Sách sắp đến hạn trả hôm nay!' : 'Sách sắp đến hạn trả trong 1 ngày!';
            $message = "Cuốn sách '{$loan->book->title}' của bạn sẽ đến hạn trả vào {$loan->due_date->format('d/m/Y')}. Vui lòng trả sách đúng hạn.";
            
            $notification = $user->createNotification('loan_due', $title, $message, [
                'loan_id' => $loan->id,
                'book_id' => $loan->book_id,
                'due_date' => $loan->due_date->toDateString(),
            ]);

            // Send email notification
            $this->sendEmailNotification($user, $notification);
        }
    }

    /**
     * Send loan overdue notification
     */
    public function sendLoanOverdueNotification(Loan $loan)
    {
        $user = $loan->user;
        
        if (!$user->wantsNotification('loan_overdue') || !$user->email_notifications_enabled) {
            return;
        }

        $daysOverdue = now()->diffInDays($loan->due_date);
        
        if ($daysOverdue > 0) {
            $title = 'Sách đã quá hạn trả!';
            $message = "Cuốn sách '{$loan->book->title}' của bạn đã quá hạn trả {$daysOverdue} ngày. Vui lòng trả sách sớm nhất có thể.";
            
            $notification = $user->createNotification('loan_overdue', $title, $message, [
                'loan_id' => $loan->id,
                'book_id' => $loan->book_id,
                'due_date' => $loan->due_date->toDateString(),
                'days_overdue' => $daysOverdue,
            ]);

            // Send email notification
            $this->sendEmailNotification($user, $notification);
        }
    }

    /**
     * Send book returned confirmation
     */
    public function sendBookReturnedConfirmation(Loan $loan)
    {
        $user = $loan->user;
        
        if (!$user->wantsNotification('book_returned') || !$user->email_notifications_enabled) {
            return;
        }

        $title = 'Đã trả sách thành công!';
        $experiencePoints = $loan->experience_points ?? 0;
        $message = "Cảm ơn bạn đã trả cuốn sách '{$loan->book->title}'. Bạn đã nhận được {$experiencePoints} điểm kinh nghiệm!";
        
        $notification = $user->createNotification('book_returned', $title, $message, [
            'loan_id' => $loan->id,
            'book_id' => $loan->book_id,
            'experience_points' => $experiencePoints,
        ]);

        // Send email notification
        $this->sendEmailNotification($user, $notification);
    }

    /**
     * Send new discussion notification to group members
     */
    public function sendNewDiscussionNotification(Discussion $discussion, ReadingGroup $group = null)
    {
        if (!$group) {
            return;
        }

        $members = $group->members()->where('user_id', '!=', $discussion->user_id)->get();
        
        foreach ($members as $member) {
            if (!$member->wantsNotification('new_discussion') || !$member->email_notifications_enabled) {
                continue;
            }

            $title = 'Thảo luận mới trong nhóm đọc';
            $message = "{$discussion->user->name} đã tạo thảo luận mới '{$discussion->title}' trong nhóm '{$group->name}'.";
            
            $notification = $member->createNotification('new_discussion', $title, $message, [
                'discussion_id' => $discussion->id,
                'group_id' => $group->id,
                'author_id' => $discussion->user_id,
            ]);

            // Send email notification
            $this->sendEmailNotification($member, $notification);
        }
    }

    /**
     * Send new reply notification
     */
    public function sendNewReplyNotification(Discussion $discussion, $replyAuthor)
    {
        $discussionAuthor = $discussion->user;
        
        if ($discussionAuthor->id === $replyAuthor->id) {
            return; // Don't notify the same user
        }

        if (!$discussionAuthor->wantsNotification('new_reply') || !$discussionAuthor->email_notifications_enabled) {
            return;
        }

        $title = 'Có phản hồi mới cho thảo luận của bạn';
        $message = "{$replyAuthor->name} đã trả lời thảo luận '{$discussion->title}' của bạn.";
        
        $notification = $discussionAuthor->createNotification('new_reply', $title, $message, [
            'discussion_id' => $discussion->id,
            'reply_author_id' => $replyAuthor->id,
        ]);

        // Send email notification
        $this->sendEmailNotification($discussionAuthor, $notification);
    }

    /**
     * Send reading group invite notification
     */
    public function sendReadingGroupInviteNotification(User $user, ReadingGroup $group, User $inviter)
    {
        if (!$user->wantsNotification('reading_group_invite') || !$user->email_notifications_enabled) {
            return;
        }

        $title = 'Lời mời tham gia nhóm đọc';
        $message = "{$inviter->name} đã mời bạn tham gia nhóm đọc '{$group->name}'.";
        
        $notification = $user->createNotification('reading_group_invite', $title, $message, [
            'group_id' => $group->id,
            'inviter_id' => $inviter->id,
        ]);

        // Send email notification
        $this->sendEmailNotification($user, $notification);
    }

    /**
     * Send badge earned notification
     */
    public function sendBadgeEarnedNotification(User $user, $badgeName)
    {
        if (!$user->wantsNotification('badge_earned') || !$user->email_notifications_enabled) {
            return;
        }

        $title = 'Chúc mừng! Bạn đã nhận được huy hiệu mới!';
        $message = "Bạn đã nhận được huy hiệu '{$badgeName}'. Tiếp tục phát huy nhé!";
        
        $notification = $user->createNotification('badge_earned', $title, $message, [
            'badge_name' => $badgeName,
        ]);

        // Send email notification
        $this->sendEmailNotification($user, $notification);
    }

    /**
     * Send level up notification
     */
    public function sendLevelUpNotification(User $user, $newLevel)
    {
        if (!$user->wantsNotification('level_up') || !$user->email_notifications_enabled) {
            return;
        }

        $title = 'Chúc mừng! Bạn đã lên cấp!';
        $message = "Bạn đã lên cấp {$newLevel}! Tiếp tục đọc sách để lên cấp cao hơn nhé!";
        
        $notification = $user->createNotification('level_up', $title, $message, [
            'new_level' => $newLevel,
        ]);

        // Send email notification
        $this->sendEmailNotification($user, $notification);
    }

    /**
     * Send email notification using Mailtrap
     */
    private function sendEmailNotification(User $user, Notification $notification)
    {
        try {
            Mail::send('emails.notification', [
                'user' => $user,
                'notification' => $notification
            ], function ($message) use ($user, $notification) {
                $message->to($user->email, $user->name)
                        ->subject($notification->title);
            });

            $notification->markEmailSent();
            
            Log::info('Email notification sent successfully', [
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'type' => $notification->type
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Process overdue loans and send notifications
     */
    public function processOverdueLoans()
    {
        $overdueLoans = Loan::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->with(['user', 'book'])
            ->get();

        foreach ($overdueLoans as $loan) {
            $this->sendLoanOverdueNotification($loan);
        }

        Log::info('Processed overdue loans', ['count' => $overdueLoans->count()]);
    }

    /**
     * Process due loans and send reminders
     */
    public function processDueLoans()
    {
        $dueLoans = Loan::where('status', 'borrowed')
            ->whereBetween('due_date', [now(), now()->addDay()])
            ->with(['user', 'book'])
            ->get();

        foreach ($dueLoans as $loan) {
            $this->sendLoanDueReminder($loan);
        }

        Log::info('Processed due loans', ['count' => $dueLoans->count()]);
    }
}
