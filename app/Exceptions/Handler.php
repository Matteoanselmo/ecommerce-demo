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
        // Mappa colori basata sui codici di stato
        $statusColors = [
            200 => 'success',
            201 => 'success',
            400 => 'warning',
            401 => 'warning',
            403 => 'warning',
            404 => 'error',
            500 => 'error',
        ];

        // Per le richieste API (risposta JSON)
        if ($request->wantsJson()) {
            $statusCode = 500;
            $message = 'Something went wrong';
            $color = 'error'; // Colore di default

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $statusCode = 404;
                $message = 'Resource not found';
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $statusCode = $exception->getStatusCode();
                $message = $exception->getMessage() ?: 'Something went wrong';
            }

            // Assegna il colore in base allo status code
            $color = $statusColors[$statusCode] ?? 'error';

            return response()->json([
                'message' => $message,
                'status_code' => $statusCode,
                'color' => $color,
            ], $statusCode);
        }

        // Per le richieste HTTP normali (come pagine web)
        $statusCode = 500;
        $message = 'Something went wrong';
        $color = 'error'; // Colore di default per pagine web

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $statusCode = 404;
            $message = 'Page not found';
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage() ?: 'Something went wrong';
        }

        // Assegna il colore in base allo status code per le richieste web
        $color = $statusColors[$statusCode] ?? 'error';

        return inertia('ErrorPage', [
            'statusCode' => $statusCode,
            'message' => $message,
            'color' => $color,
        ]);
    }
}
