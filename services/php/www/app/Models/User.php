<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Language;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Models\PlayerStat;
use App\Models\Card;



class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'experience',
        'language',
        'status',
        'is_bot'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'language' => Language::class,
            'status' => UserStatus::class,
            'is_bot' => 'boolean'
        ];
    }

    public function oauthIdentities(): HasMany
    {
        return $this->hasMany(OAuthIdentity::class);
    }

    // public function friendsOfMine()
    // {
    //     return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
    //                 ->withPivot('status')
    //                 ->wherePivot('status', 'accepted');
    // }

    // public function friendOf()
    // {
    //     return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
    //                 ->withPivot('status')
    //                 ->wherePivot('status', 'accepted');
    // }

	public function friendsOfMine()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->withPivot('status', 'requester_id', 'chat_id'); // Solo withPivot
    }

    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
                    ->withPivot('status', 'requester_id', 'chat_id'); // Solo withPivot
    }

    public function updateAvatar($avatar)
    {
        $path = $avatar->store('media/avatars', 'public');

        if ($this->avatar) 
        {
            Storage::disk('public')->delete($this->avatar);
        }

        $this->avatar = $path;
    }


    // IVAN
    public function stats()
    {
        return $this->hasOne(PlayerStat::class, 'user_id');
    }

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_user', 'user_id', 'card_id');
    }
}
