<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
        if ($this->shouldReport($exception) && ! \App::isLocal()) {
            try {
                $this->sendMail($exception);
            } catch (Exception $e) {
                dd($e);
            }
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($this->isHttpException($e))
        {
            if($e instanceof NotFoundHttpException) {
                \View::share('breadcrumbs', [['name' => 404]]);
                return response()->view('errors.404', ['message' => $e->getMessage()], 404, ['Content-type' => 'text/html; charset=utf-8']);
            }
            return $this->renderHttpException($e);
        } elseif($this->shouldReport($e) && ! \App::isLocal()) {
            return response()->view('errors.500', ['message' => $e->getMessage()], 500);
        }
        return parent::render($request, $e);
    }


    protected function sendMail($e)
    {
        $text = "url: " . \Request::fullUrl();
        $text .= " \n\rmessage: " . $e->getMessage();
        $text .= " \n\rfile: " . $e->getFile();
        $text .= " \n\rline: " . $e->getLine();

        \Mail::raw($text, function ($message) {
            $message->to(config('app.dev_email'), $name = null);
            $message->subject('Exception');
        });
    }
}
