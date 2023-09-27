<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Mail;
//use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
//use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use App\Mail\ExceptionMail;
use Illuminate\Support\Facades\Log;

//use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            $this->sendExceptionEmail($exception);
        }
        parent::report($exception);
    }
    /**
     * Parse the exception and send email
     *
     * @param Exception $exception
     */
    public function sendExceptionEmail(Throwable $exception)
    {
        try {
            $e = FlattenException::create($exception);
            //$handler = new SymfonyExceptionHandler();
            $handler = new HtmlErrorRenderer(true);
            //$html = $handler->getHtml($e);
            $html = $handler->getBody($e);
            Mail::queue(new ExceptionMail($html));
        } catch (Exception $e) {
            Log::error('Send Exception Email Exception : '. $e);
        }
    }
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        /**
         * Had to put this in because it was throwing an exception if the user wasn't unauthenticated
         */
        if ( ! $this->shouldReport($exception)) {
            return parent::render($request, $exception);
        }
        if(config('app.debug')) {
            return parent::render($request, $exception);
        }
        return response()->view('errors.500', [], 500);
    }

}
