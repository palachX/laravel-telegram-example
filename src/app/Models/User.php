<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property UserState $state
 */
final class User extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        'phone',
        'telegram_id',
        'username',
        'first_name',
        'last_name',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function updatePhoneNumber(string $phone): bool
    {
        return $this->update([
            'phone' => $phone,
        ]);
    }

    /**
     * @return HasOne<UserState, $this>
     */
    public function state(): HasOne
    {
        return $this->hasOne(UserState::class)->latest();
    }
}
