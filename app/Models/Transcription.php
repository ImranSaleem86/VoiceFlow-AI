<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Transcription extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transcriptions';
    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'audio_file_path',
        'transcription',
        'status',
        'error_message',
    ];

    protected static function booted()
    {
        static::creating(function ($transcription) {
            $transcription->user_id = auth()->id();
        });
    }
    
    /**
     * Get the project that owns the transcription.
     */
    public function project()
    {
        return $this->belongsTo(Project::class)->where('user_id', auth()->id());
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getStatusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'failed' => 'Failed',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
