<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Episode extends Model
{
    use HasFactory;

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

     /**
     * @return Attribute<string, *never>
     */
    public function formattedLength(): Attribute
    {
        return Attribute::make(

            get: function ($value, array $attributes) {

                $hours = floor($attributes['length_in_minutes'] / 60);
                $hours_string = $hours > 0 ? $hours . ' ' . Str::plural('hr', $hours) . ' ' : '';

                $reminderMinutes = $attributes['length_in_minutes'] % 60;
                $minutesString = $reminderMinutes .  ' ' . Str::plural('min', $reminderMinutes);

                return $hours_string . $minutesString;
            }
        );
    }
}
