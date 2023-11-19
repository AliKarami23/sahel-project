<?php

namespace Modules\Sms\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use IPPanel\Client;

class SmsController extends Controller
{
    public function VerificationCode($Phone_Number,$Verification_Code){

        $client = new Client("pF-rVvosQ19AgswQVfClvUSLwboB_F6fSbaQ3H4z0jk=");
        $client->sendPattern("79qkp2j9rg6d2rk","+9890000145",$Phone_Number,[
            "Verification_Code" => $Verification_Code,
        ]);
    }
}
