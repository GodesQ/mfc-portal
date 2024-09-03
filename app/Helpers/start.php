<?php
use App\Models\User;

if(!function_exists("generateNextMfcId")) {
    function generateNextMfcId()
    {
        // Get the latest MFC ID from the database
        $lastUser = User::orderBy('mfc_id_number', 'desc')->first();

        // Extract the numeric part and increment it
        if ($lastUser && $lastUser->mfc_id_number) {
            $lastNumberPart = (int)substr($lastUser->mfc_id_number, 4);
            $nextNumber = $lastNumberPart + 1;
        } else {
            // Start from 1 if no MFC ID exists
            $nextNumber = 1;
        }

        // Pad the number with leading zeros to maintain the length of 7 digits
        $newNumberPart = str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

        // Concatenate with the prefix "MFC-"
        return "MFC-" . $newNumberPart;
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

