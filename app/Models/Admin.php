<?php

namespace App\Models;

use App\QueryBuilders\AdminQueryBuilder;
use App\Traits\HasSchemaAccessors;
use App\Traits\HasCustomShortflakePrimary;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class Admin
 *
 * Represents an Admin in the application.
 */
class Admin extends Authenticatable implements HasLocalePreference, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia, HasCustomShortflakePrimary, HasSchemaAccessors;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'meta' => 'array',
        'created_at' => 'datetime',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should not be exposed by API and other public responses.
     *
     * @var array
     */
    protected $hiddenForPublic = [
        'display_name',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'country_code',
        'phone_prefix',
        'phone_country',
        'phone',
        'phone_e164',
        'is_active',
        'is_undeletable',
        'is_uneditable',
        'meta',
        'media',
        'deleted_at',
        'deleted_by',
        'created_by',
        'updated_by'
    ];

    public function hideForPublic()
    {
        $this->makeHidden($this->hiddenForPublic);

        return $this;
    }

    /**
     * Allow mass assignment of a model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Append programmatically added columns.
     *
     * @var array
     */
    protected $appends = [
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  mixed  $query
     * @return AdminQueryBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new AdminQueryBuilder($query);
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        $locale = $this->locale;
        $defaultLocale = config('app.locale');

        return File::exists(lang_path() . '/' . $locale) ? $locale : $defaultLocale;
    }

    /**
     * Check if user has a specific role.
     *
     * @param  array|string  $roles
     */
    public function hasRole($roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return in_array($this->role, $roles);
    }

    /**
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                // First conversion: small
                $this
                    ->addMediaConversion('small')
                    ->fit(Fit::Max, 80, 80)
                    ->keepOriginalImageFormat();

                // Second conversion: medium
                $this
                    ->addMediaConversion('medium')
                    ->fit(Fit::Max, 320, 320)
                    ->keepOriginalImageFormat();
            });
    }

    /**
     * Retrieve the value of an attribute or a dynamically generated image URL.
     *
     * @param  string  $key The attribute key or the image key with a specific conversion.
     * @return mixed The value of the attribute or the image conversion URL.
     *
     * @throws \Illuminate\Database\Eloquent\RelationNotFoundException If the relationship is not found.
     */
    public function __get($key)
    {
        if (substr($key, 0, 7) === 'avatar-') {
            return $this->getAvatarUrl(substr($key, 7, strlen($key)));
        }

        return parent::__get($key);
    }

    /**
     * Get the avatar URL.
     *
     * @return string|null
     */
    public function getAvatarAttribute()
    {
        return $this->getAvatarUrl();
    }

    /**
     * Get the avatar URL with a specific conversion.
     *
     * @param  string|null  $conversion
     * @return string|null
     */
    public function getAvatarUrl($conversion = '')
    {
        if ($this->getFirstMediaUrl('avatar') !== '') {
            $media = $this->getMedia('avatar');

            // Get the resized image URL with the specified conversion
            return $media[0]->getFullUrl($conversion);
        } else {
            return null;
        }
    }

    /**
     * Get the networks associated with the admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function networks()
    {
        return $this->belongsToMany(Network::class, 'admin_network');
    }

    /**
     * Get the partners associated with the admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function partners()
    {
        return $this->hasMany(Partner::class);
    }
}