<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TempCampaignEmails extends Model
{
   	protected $table = 'temp_campaign_emails';
    public $timestamps = false;

   	protected $fillable = [
        'email',
        'name',
        'user_type',
        'campaign_type',
        'temp_campaign_email_content_id'
    ];


    public function campaign(){
        return $this->belongsTo('\App\TempCampaignEmailContent', 'temp_campaign_email_content_id');
    }
}
