<?php namespace App\Models;

use Cmgmyr\Messenger\Models\Thread as BaseThread;
use Illuminate\Support\Facades\Auth;

class Thread extends BaseThread
{
    protected $appends = ['replies', 'is_unread'];

    public function getRepliesAttribute()
    {
        return $this->messages()->exists() ? $this->messages()->count() - 1 : 0;
    }

    public function getIsUnreadAttribute()
    {
        return $this->isUnread(Auth::id());
    }
}