<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'name_prefix',
        'first_name',
        'middle_initial',
        'last_name',
        'gender',
        'email',
        'date_of_birth',
        'time_of_birth',
        'age_in_yrs',
        'date_of_joining',
        'age_in_company_years',
        'phone_no',
        'place_name',
        'county',
        'city',
        'zip',
        'region',
        'user_name',
    ];
    
}
