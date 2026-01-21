<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserStateEnum;
use Database\Factories\UserStateFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class UserState extends Model
{
    /** @use HasFactory<UserStateFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        'user_id',
        'state',
    ];

    protected $casts = [
        'user_id' => 'string',
        'state' => UserStateEnum::class,
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
