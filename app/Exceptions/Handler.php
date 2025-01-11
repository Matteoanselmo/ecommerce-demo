<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler {
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception) {
        $statusColors = [
            200 => 'success',
            201 => 'success',
            400 => 'warning',
            401 => 'warning',
            403 => 'warning',
            404 => 'error',
            422 => 'warning',
            500 => 'error',
        ];

        // Gestione degli errori di validazione
        if ($exception instanceof ValidationException) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $exception->errors(),
                    'status_code' => 422,
                    'color' => 'warning',
                ], 422);
            }
        }

        // Gestione delle richieste JSON
        if ($request->wantsJson()) {
            $statusCode = 500;
            $message = 'Something went wrong';
            $color = 'error';

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $statusCode = 404;
                $message = 'Resource not found';
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $statusCode = $exception->getStatusCode();
                $message = $exception->getMessage() ?: 'Something went wrong';
            } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                $statusCode = 401;
                $message = 'Unauthenticated';
            }

            return response()->json([
                'message' => $message,
                'status_code' => $statusCode,
                'color' => $statusColors[$statusCode] ?? 'error',
            ], $statusCode);
        }

        // Per richieste HTTP normali
        if (!$request->expectsJson()) {
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return inertia('ErrorPage', [
                    'statusCode' => 404,
                    'message' => 'Page not found',
                    'color' => 'error',
                ]);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return inertia('ErrorPage', [
                    'statusCode' => $exception->getStatusCode(),
                    'message' => $exception->getMessage() ?: 'Something went wrong',
                    'color' => $statusColors[$exception->getStatusCode()] ?? 'error',
                ]);
            }
        }

        // Default error handler
        return parent::render($request, $exception);
    }
}
