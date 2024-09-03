<?php
use App\Models\User;

if(!function_exists("generateNextMfcId")) {
    function generateNextMfcId()
    {
        $mfc_number = generateRandomSevenNumber();

        $user = User::select('mfc_id_number')->where('mfc_id_number', $mfc_number)->exists();

        while($user) {
                $mfc_number = generateRandomSevenNumber();
        }

        return $mfc_number;
    }
}

if(!function_exists("generateRandomSevenNumber")) {
    function generateRandomSevenNumber() {
        return rand(1000000, 9999999);
    }
}

if(!function_exists("generateTransactionCode")) {
    function generateTransactionCode() {
        $transaction_code = "TRX-" . date("Y") . date("m") . '-' . Str::random(10);
        return $transaction_code;
    }
}

if(!function_exists("generateReferenceCode")) {
    function generateReferenceCode() {
        $reference_code = "REF" . date("Y") . date("m") . "-" . Str::random(6);
        return $reference_code;
    }
}

