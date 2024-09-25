<?php

namespace App\Services;

class ExceptionHandlerService
{
    public function __handler($request, $exception)
    {
        if ($request->accepts('application/json')) {
            return $this->__handleJSONResponse($exception);
        }

        return $this->__handleHTMLResponse($exception);
    }

    public function __handleJSONResponse($exception)
    {
        $resultCode = $this->__getExceptionCode($exception);

        $result = [
            'error' => $exception,
            'message' => $resultCode == 500 ? self::serverErrorMessage() : $exception->getMessage(),
        ];

        return response()->json($result, $resultCode);
    }

    public function __handleHTMLResponse($exception)
    {
        return back()->with('failed', $exception->getMessage());
    }

    private function __getExceptionCode($exception)
    {
        $exception_code = $exception->getCode();
        $result_code = $exception_code == 0 || is_nan($exception_code) ? 500 : (int) $exception_code;

        return $result_code;
    }

    private static function serverErrorMessage()
    {
        return "Server Error Found. Please contact customer service to resolved the issue.";
    }
}