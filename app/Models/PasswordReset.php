<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPasswordReset
 */
class PasswordReset extends Model
{
    protected $fillable = ['email', 'token'];
}
