<?php

namespace App\Exceptions;
use Throwable;

use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


class Handler extends ExceptionHandler
{
    use ApiResponser;
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
            //
        });
    }

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->set_response(null,401,'error',['Unauthenticated.']);
    }


    public function render($request, Throwable $e)
    {
        if (\config('app.debug'))
        {
            return parent::render($request, $e);
        }

        $status = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($e instanceof HttpResponseException)
        {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        elseif ($e instanceof MethodNotAllowedHttpException)
        {
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $e = new MethodNotAllowedHttpException([], ('Method not allowed!'), $e);
        }
        elseif ($e instanceof NotFoundHttpException)
        {
            $status = Response::HTTP_NOT_FOUND;
            $e = new NotFoundHttpException(('Not found!'));
        }
        elseif ($e instanceof AuthorizationException)
        {
            $status = Response::HTTP_FORBIDDEN;
            $e = new AuthorizationException(('Forbidden request! You do not have the required permission to access.'), $status);
        }
        elseif ($e instanceof \Dotenv\Exception\ValidationException && $e->getResponse())
        {
            $status = Response::HTTP_BAD_REQUEST;
            $e = new \Dotenv\Exception\ValidationException(('Bad Request!'), $status, $e);
        }
        elseif ($e instanceof AuthenticationException)
        {
            $status = 401;
            $e = new HttpException($status, 'Unauthenticated!');
        }
        elseif ($e instanceof ThrottleRequestsException)
        {
            $retry_after = $e->getHeaders()['Retry-After'] ?? 0;
            $status = 429;
            $e = new HttpException($status, 'Too many requests! Try after '.$retry_after.' seconds!');
        }
        elseif ($e)
        {
            $e = new HttpException($status, 'Internal Server Error!');
        }


        return $this->set_response(null, $status, 'error', [$e->getMessage()]);

        return parent::render($request, $e);
    }
}
