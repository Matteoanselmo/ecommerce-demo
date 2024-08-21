<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
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
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
{
    $statusCode = 500;
    $message = 'Something went wrong';

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
        $statusCode = 404;
        $message = 'Page not found';
    } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
        $statusCode = $exception->getStatusCode();
        $message = $exception->getMessage() ?: 'Something went wrong';
    }

    return inertia('ErrorPage', [
        'statusCode' => $statusCode,
        'message' => $message
    ]);
}
}
