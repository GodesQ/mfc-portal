<?php
use App\Models\User;

if (! function_exists("generateNextMfcId")) {
    function generateNextMfcId()
    {
        $mfc_number = generateRandomSevenNumber();

        $user = User::select('mfc_id_number')->where('mfc_id_number', $mfc_number)->exists();

        while ($user) {
            $mfc_number = generateRandomSevenNumber();
        }

        return $mfc_number;
    }
}

if (! function_exists("generateNewMFCId")) {
    function generateNewMFCId()
    {
        // Fetch the latest MFC ID from the database
        $latestMFC = User::orderBy('mfc_id_number', 'desc')->first();

        // Default starting ID if no previous ID exists
        $defaultPrefix = 'MFCPH';
        $defaultNumber = '000001';

        if (! $latestMFC) {
            // If there are no existing records, return the default MFC ID
            return $defaultPrefix . $defaultNumber;
        }

        // Extract the numeric part from the latest MFC ID (e.g., "MFCPH000036" -> "000036")
        $latestId = $latestMFC->mfc_id_number;
        $numberPart = (int) substr($latestId, 5); // "000036" -> 36

        // Increment the number
        $newNumber = $numberPart + 1;

        // Format the new number with leading zeros (e.g., 37 -> "000037")
        $newMfcId = $defaultPrefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        return $newMfcId;
    }

}

if (! function_exists("generateRandomSevenNumber")) {
    function generateRandomSevenNumber()
    {
        return rand(1000000, 9999999);
    }
}

if (! function_exists("generateTransactionCode")) {
    function generateTransactionCode()
    {
        $transaction_code = "TRX-" . date("Y") . date("m") . '-' . Str::random(10);
        return $transaction_code;
    }
}

if (! function_exists("generateReferenceCode")) {
    function generateReferenceCode()
    {
        $reference_code = "REF" . date("Y") . date("m") . "-" . Str::random(6);
        return $reference_code;
    }
}

if (! function_exists("getMFCArea")) {
    function getMFCArea()
    {
        return [
            "ncr_north", "ncr_south", "ncr_east", "ncr_central", "south_luzon",
            "north_and_central_luzon", "visayas", "mindanao", "international", "baguio",
            "palawan", "batangas", "laguna", "pampanga", "tarlac"
        ];
    }
}

