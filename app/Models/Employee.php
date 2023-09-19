<?php

namespace App\Models;

use App\Enums\Gender;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'username',
        'first_name',
        'last_name',
        'middle_initial',
        'gender',
        'email',
        'date_of_birth',
        'time_of_birth',
        'age_in_years',
        'phone_number',
        'place_name',
        'date_of_joining',
        'age_in_company_in_years',
        'prefix_id',
        'zip_code_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function getTimeOfBirthAttribute($value)
    {
        $this->attributes['time_of_birth'] = Carbon::createFromFormat(strtotime($value))->format('h:i:s A');
    }

    public function getGenderAttribute($value)
    {
        if ($value === Gender::FEMALE->value) {
            return Gender::FEMALE->name;
        } elseif ($value === Gender::MALE->value) {
            return Gender::MALE->name;
        }
    }

    public function prefix(): BelongsTo
    {
        return $this->belongsTo(Prefix::class, 'prefix_id');
    }

    public function zipCode(): BelongsTo
    {
        return $this->belongsTo(ZipCode::class, 'zip_code_id');
    }

    public function city(): HasOneThrough
    {
        return $this->hasOneThrough(
            City::class,
            ZipCode::class,
            'id',
            'id',
            'zip_code_id',
            'city_id'
        );
    }

    public function county(): HasOneThrough
    {
        return $this->hasOneThrough(
            County::class,
            City::class,
            'id',
            'id',
            'zip_code_id',
            'county_id'
        );
    }

    public function region(): HasOneThrough
    {
        return $this->hasOneThrough(
            Region::class,
            County::class,
            'id',
            'id',
            'zip_code_id',
            'region_id'
        );
    }
}
