<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Modules\Core\Transformers\BaseResource;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @var null
     */
    protected $repository;

    /**
     * Status code of the Response
     *
     * @var integer
     */
    private int $status_code = 200;

    /**
     * @var
     */
    protected  $transformer = null;

    public function __construct($repository = null)
    {
        $this->repository = $repository;
    }

    protected function getTransformer()
    {
        // Check if controller specifies a resource
        if (!is_null($this->transformer)) {
            $transformer = $this->transformer;
        }else{
            $transformer = BaseResource::class;
        }

        return  $transformer;
    }

    /**
     * Get the HTTP status code
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * Set the HTTP status code
     *
     * @param $status_code
     * @return $this
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;

        return $this;
    }

    /**
     * Generate a Response with array of data.
     *
     * @param array $array
     * @param array $headers
     * @return JsonResponse
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        return response()->json($array, $this->status_code, $headers);
    }

    /**
     * Generate a Response with error and messages.
     *
     * @param string $message
     * @param array $errors
     * @return JsonResponse
     */
    protected function respondWithError($message, $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        return $this->respondWithArray($response);
    }

    /**
     * Generate a Response with a 200 HTTP header and a given message.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondOK($message = 'Done')
    {
        return $this->setStatusCode(200)->respondWithArray([
            'message' => $message,
            'success' => true
        ]);
    }

    /**
     * Generate a Response with a 201 HTTP header and a given message.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondCreated($message = 'Successfully created')
    {
        return $this->setStatusCode(201)->respondWithArray([
            'message' => $message,
            'success' => true
        ]);
    }

    /**
     * Generate a Response with a 403 HTTP header and a given message.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function errorInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function errorBadRequest($message = 'Bad Request')
    {
        return $this->setStatusCode(400)->respondWithError($message);
    }

    /**
     * Generate Response based on a single item
     *
     * @param object|string $item
     * @param string $message
     * @return mixed
     */
    protected function respondWithItem($item, $message= '')
    {
        $transformer = $this->getTransformer();
        $transformer = new $transformer($item);

        $additional = ['success' => true];
        if(!empty($message)) $additional['message'] = $message;

        return  ($transformer)->additional($additional);
    }

    /**
     * Generate Response based on a single item
     *
     * @param object|string $items
     * @param string $message
     * @return mixed
     */
    protected function respondWithCollection($items, $message = '')
    {
        $transformer = $this->getTransformer();
        return $transformer::collection($items);
    }

    protected function exceptionResponse($e)
    {
        //Log or Report the exception
        report_exception($e);

        if(App::environment('testing')) throw $e;

        $message = __('An Error Occurred');

        if ($e instanceof ModelNotFoundException) {
            return $this->errorNotFound(__('Resource not found'));
        }

        if($e instanceof AuthorizationException) return $this->errorUnauthorized($e->getMessage());

        if ($e instanceof BadRequestException) {
            return $this->errorBadRequest($e->getMessage());
        }

        return $this->errorForbidden($message);
    }
}
