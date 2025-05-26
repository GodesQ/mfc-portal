<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class ExceptionHandlerService
{
    public function handler($request, Exception $exception)
    {
        Log::error($exception->getMessage(), ['exception' => $exception]); // Logging error

        if ($request->expectsJson() || $request->ajax()) {
            return $this->handleJSONResponse($exception);
        }

        return $this->handleHTMLResponse($exception);
    }

    public function handleJSONResponse(Exception $exception)
    {
        $resultCode = $this->getExceptionCode($exception);

        $result = [
            'error' => class_basename($exception),
            'message' => $resultCode == 500 ? self::serverErrorMessage() : ($exception->getMessage() ?? "Oops! Something wen't wrong. Please try again."),
        ];

        if (config('app.debug')) {
            $result['debug'] = [
                'ip_address' => request()->ip(),
                'trace' => $exception->getTrace(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];
        }

        return response()->json($result, $resultCode);
    }

    public function handleHTMLResponse(Exception $exception)
    {
        $exceptionCode = $this->getExceptionCode($exception);
        $exceptionMessage = config('app.debug') ? ($exception->getMessage() ?? "Oops! Something wen't wrong. Please try again.") : self::serverErrorMessage();

        return view('error.auth-500', ['message' => $exceptionMessage, 'exception' => $exception, 'status_code' => $exceptionCode]);
    }

    private function getExceptionCode(Exception $exception)
    {
        $exception_code = $exception->getCode();

        // Ensure the code is numeric and within valid HTTP status codes (100-599)
        return is_numeric($exception_code) && $exception_code >= 100 && $exception_code <= 599
            ? (int) $exception_code
            : 500;
    }

    private static function serverErrorMessage()
    {
        return "Server Error Found. Please screenshot this error/issue and contact customer service to resolve the issue.";
    }
}
