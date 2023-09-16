<?php

namespace App\Exceptions;

use App\Traits\GeneralResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use GeneralResponseTrait;

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

    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return $e->render();
        } else if ($e instanceof ModelNotFoundException) {
            return $this->returnError($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $e);
    }
}
