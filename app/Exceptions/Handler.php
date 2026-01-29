<?php namespace App\Exceptions;

//use App\Mail\ExceptionMail;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
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
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()
                ->back()
                ->withInput($request->except('password'))
                ->with('errorMessage', 'Este formulario ha caducado por inactividad. Inténtalo de nuevo.');
        }

        return parent::render($request, $exception);
    } // render

    /**
     * Sends an email to the developer about the exception.
     *
     * @return void
     */
    public function sendExceptionEmail(Throwable $exception)
    {
        try {
            $e = FlattenException::createFromThrowable($exception);
            $handler = new HtmlErrorRenderer(true);
            $css = $handler->getStylesheet();
            $content = $handler->getBody($e);
            $user = Auth::user();
            if( $user ) {
                $data = [
                    'uid' => ($user) ? $user->user_id : 'N/A',
                    'name' => ($user) ? $user->name : 'N/A',
                    'role' => ($user) ? $user->role : 'N/A',
                    'date' => Carbon::now()->format('Y-m-d H:i:s'),
                ];
                //Mail::queue(new ExceptionMail($html));
                Mail::send('emails.email_exception', compact('css','content','data'), function ($message) {
                    $message
                        ->to('sonoco@iso-one.com')
                        ->subject('Exception: ' . \Request::fullUrl())
                    ;
                }); 
            }           
        } catch (Throwable $e) {
            Log::error('Handler::sendExceptionEmail Exception: '. $e->getMessage());
        }
    } // sendExceptionEmail   

} // class
