<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Setting;

class RenewMembershipNotificationMailFields extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = $this->findSetting('admin.renewal_sms_message');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('Renewal SMS message'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text_area',
                'order'        => 6,
                'group'        => 'Admin',
            ])->save();
        }
        $setting = $this->findSetting('admin.renewal_mail_message');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('Renewal Mail message'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text_area',
                'order'        => 7,
                'group'        => 'Admin',
            ])->save();
        }
    }
    protected function findSetting($key)
    {
        return Setting::firstOrNew(['key' => $key]);
    }
}
