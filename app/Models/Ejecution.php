<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejecution extends Model
{
    protected $fillable = ['id', 'potential_id','flokzu','to_flokzu','to_data','potential_no','created_at', 'updated_at']; 

}
