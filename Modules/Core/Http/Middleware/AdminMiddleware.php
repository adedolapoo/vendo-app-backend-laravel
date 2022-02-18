<?php namespace Modules\Core\Http\Middleware;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\Store;
use Modules\Users\Repositories\AuthenticationInterface as Authentication;

class AdminMiddleware
{
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var SessionManager
     */
    private $session;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Redirector
     */
    private $redirect;
    /**
     * @var Application
     */
    private $application;

    public function __construct(Authentication $auth, Store $session, Request $request, Redirector $redirect, Application $application)
    {
        $this->auth = $auth;
        $this->session = $session;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->application = $application;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (!$this->auth->check()) {
            // Store the current uri in the session
            $this->session->put('url.intended', $this->request->url());

            // Redirect to the login page
            return $this->redirect->route('login');
        }

        return $next($request);
    }
}
