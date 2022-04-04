<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class uploadPlan extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $image;
    public $building_name;
    public $country;
    public $city;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $image, $building_name, $country, $city)
    {
        $this->user = $user;
        $this->image = $image;
        $this->building_name = $building_name;
        $this->country = $country;
        $this->city = $city;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.upload_plan');
    }
}
