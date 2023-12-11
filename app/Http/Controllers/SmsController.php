<?php

namespace App\Http\Controllers;

use IPPanel\Client;

class SmsController extends Controller
{
    public function VerificationCode($phoneNumber,$verificationCode){

        $client = new Client("pF-rVvosQ19AgswQVfClvUSLwboB_F6fSbaQ3H4z0jk=");
        $client->sendPattern("kyaf6ibb7dqujzp","+9890000145",$phoneNumber,[
            "Verification_Code" => $verificationCode,
        ]);
    }
}
