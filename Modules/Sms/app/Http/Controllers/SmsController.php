<?php

namespace Modules\Sms\app\Http\Controllers;

use App\Http\Controllers\Controller;
use IPPanel\Client;

class SmsController extends Controller
{
    public function VerificationCode($Phone_Number,$Verification_Code){

        $client = new Client("pF-rVvosQ19AgswQVfClvUSLwboB_F6fSbaQ3H4z0jk=");
        $client->sendPattern("kyaf6ibb7dqujzp","+9890000145",$Phone_Number,[
            "Verification_Code" => $Verification_Code,
        ]);
    }
}
