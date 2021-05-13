<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TempCampaignEmailContent extends Model
{
   	protected $table = 'temp_campaign_email_content';
    public $timestamps = false;

   	protected $fillable = [
        'email_subject',
        'email_content'
    ];


    public function campaign_emails(){
        return $this->hasMany('\App\TempCampaignEmails','temp_campaign_email_content_id');
    }
}
