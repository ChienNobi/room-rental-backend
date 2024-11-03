<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'images',
        'room_type',
        'acreage',
        'city',
        'district',
        'ward',
        'detail_address',
        'lat',
        'lon',
        'rent_fee',
        'electricity_fee',
        'water_fee',
        'internet_fee',
        'extra_fee',
        'furniture',
        'contact_name',
        'contact_email',
        'contact_phone',
        'user_id',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
        ];
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($post) {
            $post->user_id = Auth::id();
            $post->images = json_encode($post->images);
        });
    }
}
