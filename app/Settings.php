<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Settings extends Model
{
	protected $table = "settings";

    protected $fillable = [
        'key', 'display_name','value','details','type','order','group',
    ];
}
