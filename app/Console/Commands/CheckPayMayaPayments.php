<?php

namespace App\Console\Commands;

use App\Enum\PaymentType;
use App\Models\Tithe;
use Illuminate\Console\Command;
use App\Models\Transaction; // Assuming you have a Transaction model
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckPayMayaPayments extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'paymaya:check-payments';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Check PayMaya payment status for recent unpaid transactions';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->info('Starting PayMaya payment status check...');

    // Get transactions created in the last 24 hours with unpaid status
    $cutoffTime = Carbon::now()->subDay();

    $transactions = Transaction::where('status', 'unpaid')
      ->where('created_at', '>=', $cutoffTime)
      ->whereNotNull('checkout_id') // Assuming you store PayMaya Checkout ID
      ->get();

    Log::info('transactions', ['transactions' => $transactions]);

    $this->info('Found ' . $transactions->count() . ' transactions to check.');

    foreach ($transactions as $transaction) {
      try {
        $this->info("Checking transaction ID: {$transaction->id}");

        // Call PayMaya API to get payment status
        $response = Http::withHeaders([
          'Authorization' => 'Basic ' . base64_encode(config('services.paymaya.test_secret_key') . ':'),
          'Content-Type' => 'application/json',
        ])->get('https://pg-sandbox.paymaya.com/payments/v1/payments/' . $transaction->checkout_id);

        if ($response->successful()) {
          $paymentData = $response->json();
          $status = strtolower($paymentData['status'] ?? null);
          $isPaid = $paymentData['isPaid'];

          $this->info("Transaction ID: {$transaction->id} - Current status: {$status}");

          // Update transaction status if it's changed
          if ($isPaid) {
            $transaction->status = 'paid';
            $transaction->save();

            if ($transaction->payment_type == PaymentType::TITHE) {
              Tithe::where('transaction_id', $transaction->id)->update([
                'status' => 'paid',
                'payment_mode' => 'maya',
              ]);
            }

            $this->info("Updated transaction ID: {$transaction->id} to status: {$status}");

            // You might want to trigger other actions here (send email, etc.)
          }
        } else {
          $this->error("Failed to check status for transaction ID: {$transaction->id}");
          Log::error('PayMaya API Error', [
            'transaction_id' => $transaction->id,
            'response' => $response->body(),
            'status_code' => $response->status(),
          ]);
        }
      } catch (\Exception $e) {
        $this->error("Error processing transaction ID: {$transaction->id} - " . $e->getMessage());
        Log::error('PayMaya Check Payments Error', [
          'transaction_id' => $transaction->id,
          'error' => $e->getMessage(),
        ]);
      }
    }

    $this->info('PayMaya payment status check completed.');
  }
}
