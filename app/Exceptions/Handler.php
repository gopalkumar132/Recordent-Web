<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Encryption\DecryptException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PDOException; 
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Symfony\Component\Debug\Exception\FlattenException;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;
use General;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        DecryptException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        // Trigger Email on 500 Error
        // if($this->shouldReport($exception)){
        //     General::triggerExceptionMail($exception);
        // }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {   
        // Trigger Email on HTTP Errors
        // if ($exception instanceof HttpException){
        //     General::triggerExceptionMail($exception);
        // }
        // dd($exception);
        //return parent::render($request, $exception);
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('login');
        }
        if(strpos(config('app.url'), 'localhost') !== false) {
            return parent::render($request, $exception);
        }else if($this->isHttpException($exception)){

            if (view()->exists('errors.'.$exception->getStatusCode()))
            {
                return response()->view('errors.'.$exception->getStatusCode(), [], $exception->getStatusCode());
            }
            
            return parent::render($request, $exception);
        }else if($exception instanceof ValidationException){
            return parent::render($request, $exception);
        }else if($exception instanceof PDOException){ //Handle pdo connection error in site.
            return response()->view('errors.500');
        }else if($exception instanceof DecryptException){ 
            return response()->view('errors.500');
        }else{
            return response()->view('errors.500');
        }
    }
}