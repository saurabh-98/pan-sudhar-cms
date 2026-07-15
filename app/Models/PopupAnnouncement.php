<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupAnnouncement extends Model
{
    protected $fillable = [

        'title',

        'slug',

        'description',

        'image',

        'button_text',

        'button_link',

        'background_color',

        'text_color',

        'show_on_login',

        'show_on_dashboard',
        
        'show_on_home',

        'show_once_per_day',

        'start_date',

        'end_date',

        'priority',

        'status'

    ];
}