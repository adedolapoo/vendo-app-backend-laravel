<?php

namespace Modules\Core\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {

        });
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Flare, Sentry, Bugsnag, etc.
     *
     * @param \Throwable $exception
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param \Throwable $exception
     * @return Response|JsonResponse
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        $code = 400;
        $message = $exception->getMessage();

        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        }

        if($exception instanceof AuthorizationException) $code = 401;

        if($exception instanceof NotFoundHttpException) $message = __('Requested URL not found');

        return response()->json([
            'success' =>false,
            'message' => $message
        ],$code);

        //return parent::render($request, $exception);
    }
}
