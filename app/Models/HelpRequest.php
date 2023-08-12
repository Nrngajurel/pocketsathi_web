<?php

namespace App\Models;

use App\Traits\Statusable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpRequest extends Model
{
    use HasFactory, Statusable;
    protected $guarded =[];
    protected $appends = ['current_status'];



    public function applies(){
        return $this->hasMany(Apply::class);
    }
}
