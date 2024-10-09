<?php

namespace App\Models;

use App\Models\Candidat;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activite extends Model
{
    use HasFactory;



    protected $fillable = ['title', 'content','uuid', 'location',  'status',  'start_date', 'end_date', 'categorie_id', '_id', 'show_in_slider', 'thumbnail_url' ,'publish_status', 'send', 'form_id', 'miniature_color', 'show_in_calendar', 'live_status', 'book_a_seat', 'is_events', 'createdAt', 'updatedAt', 'creator','number_day','number_hour'];
    protected $casts = [
        'categories' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }

    public function hashtag(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class);
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function candidat(): HasMany
    {
        return $this->hasMany(Candidat::class);
    }

    public function typEvent(): BelongsToMany
    {
        return $this->belongsToMany(TypeEvent::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
