<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Page\Metadata;


abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $layout;
    protected $data = [];
    protected $_user;

    public function before()
    {

    }

    public function after()
    {

    }

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = \Auth::user();
            if ($this->user) {
                $this->set('_user', $this->user);
            }

            return $next($request);
        });

        $this->metadata = new Metadata();
        $this->metadata->set('homepage', \Request::is('/'));
        $this->metadata->title = 'Анкета';
        $this->set('_metadata', $this->metadata);

    }

    protected function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    protected function noLayout()
    {
        $this->layout = null;

        return $this;
    }

    protected function detectLayout()
    {
        $action = \Route::getCurrentRoute()->getAction();
        preg_match('~^' . preg_quote($action['namespace'] . '\\', '~') . '([^@]+)Controller@(get|post|any)(.*)' . '~i', $action['uses'], $matches);
        $controller = $matches[1];
        $action = $matches[3];
        $this->layout = strtolower($controller . DIRECTORY_SEPARATOR . $action);
    }

    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = \View::make($this->layout);
            $this->layout->with('_token', csrf_token());
            $message = \Session::pull('_message');
            if (!is_null($message)) {
                $this->layout->with('_message', $message);
            }
            $this->layout->with($this->data);
        }
    }

    public function callAction($method, $parameters)
    {
        $this->detectLayout();
        $this->before();
        $response = parent::callAction($method, $parameters);

        if (is_null($response) && !is_null($this->layout)) {
            $this->setupLayout();
            $response = $this->layout;
        }

        $this->after();
        return $response;
    }
}