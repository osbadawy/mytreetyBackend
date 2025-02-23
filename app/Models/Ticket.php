<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperTicket
 */
class Ticket extends Model
{
    public function user(): BelongsTo
    {
    	return $this->belongsTo(User::class);
    }

    public function ticketreplies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'desc');
    }

}
