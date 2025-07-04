<?php

namespace App\Http\Controllers\Api;

use App\Enum\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Resources\TitheResource;
use App\Models\Tithe;
use App\Models\Transaction;
use App\Services\ExceptionHandlerService;
use Exception;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
  public function updateStatus(Request $request, $id)
  {
    try {
      $authUser = auth()->user();

      $transaction = Transaction::where("id", $request->id)->first();

      if ($authUser->id !== $transaction->received_from_id) {
        throw new Exception("Invalid User", 401);
      }

      $transaction->update([
        'status' => $request->status,
      ]);

      if ($transaction->payment_type == PaymentType::TITHE) {
        $tithe = Tithe::where('transaction_id', $id)->first();
        $tithe->update(['status' => $request->status]);
      }

      return response()->json([
        'status' => 'success',
        'message' => 'Transaction Updated Successfully',
        'transaction' => $transaction,
      ]);
    } catch (Exception $exception) {
      $exceptionService = new ExceptionHandlerService();
      return $exceptionService->handler($request, $exception);
    }

  }
}
