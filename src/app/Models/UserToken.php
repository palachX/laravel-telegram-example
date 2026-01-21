<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserTokenFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use InvalidArgumentException;

final class UserToken extends Model
{
    /** @use HasFactory<UserTokenFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return Attribute<string,string>
     */
    protected function token(): Attribute
    {
        return Attribute::make(
            get: static function (mixed $value) {
                if (! is_string($value)) {
                    throw new InvalidArgumentException('The given value for property token is not string');
                }

                return Crypt::decryptString($value);
            },
            set: static function (mixed $value) {
                if (is_string($value)) {
                    return Crypt::encryptString($value);
                }

                throw new InvalidArgumentException('The given value for property token is not string');
            },
        );
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
