<?php

namespace App\Http\Controllers;

use IPPanel\Client;

class SmsController extends Controller
{
    public function VerificationCode($phoneNumber,$verificationCode){

        $client = new Client("pF-rVvosQ19AgswQVfClvUSLwboB_F6fSbaQ3H4z0jk=");
        $client->sendPattern("kyaf6ibb7dqujzp","+983000505",$phoneNumber,[
            "Verification_Code" => $verificationCode,
        ]);
    }

    public function extradition($phoneNumber,$answer,$price)
    {
        $client = new Client("pF-rVvosQ19AgswQVfClvUSLwboB_F6fSbaQ3H4z0jk=");
        $client->sendPattern("pngzxst7h54io8u","+9890000145",$phoneNumber,[
            "answer" => $answer,
            "price" => $price
        ]);
    }

    public function cancellationSans($phoneNumber,$full_name,$titel, $date, $start, $end)
    {
        $client = new Client("pF-rVvosQ19AgswQVfClvUSLwboB_F6fSbaQ3H4z0jk=");
        $client->sendPattern("oc1a4y4zp9wul13","+9890000145",$phoneNumber,[
            "full_name" => $full_name,
            "product" => $titel,
            "date" => $date,
            "start" => $start,
            "end" => $end,
        ]);
    }
}
