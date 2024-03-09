<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
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

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);
        if (in_array($response->status(), [503, 401, 403, 404])) {
            return Inertia::render('Errors/Error', ['status' => $response->status()])
                ->toResponse($request)
                ->setStatusCode($response->status());
        } else if ($response->status() === 419) {
            return back()->with([
                'message' => 'La página expiró intente nuevamente',
            ]);
        }

        // if (!app()->environment(['local', 'testing']) && in_array($response->status(), [500, 503, 401, 403, 404])) {
        //     return Inertia::render('Errors/Error', ['status' => $response->status()])
        //         ->toResponse($request)
        //         ->setStatusCode($response->status());
        // } else if ($response->status() === 419) {
        //     return back()->with([
        //         'message' => 'La página expiró intente nuevamente',
        //     ]);
        // }
        return $response;
    }
}
