<?php namespace Modules\Products\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Products\Http\Requests\FormRequest;
use Modules\Products\Repositories\ProductInterface as Repository;

class ProductsController extends BaseController
{

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * List of all products
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $products = $this->repository->allPaginated();

            return $this->respondWithCollection($products);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Store a newly created product to db
     *
     * @param FormRequest $request
     * @return JsonResponse
     */
    public function store(FormRequest $request)
    {
        try {
            $data = $request->all();

            //add the seller as the currently logged in user
            $data['seller_id'] = $request->user()->id;

            $product = $this->repository->create($data);

            return $this->respondWithItem($product);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Update
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $product = $this->repository->byId($id);

            return $this->respondWithItem($product);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Update the a given user identified by ID
     *
     * @param FormRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(FormRequest $request, $id)
    {
        try {
            $data = $request->all();
            $product = $this->repository->byId($id);

            //authorize that a user can update the post he created
            $this->authorize('update', $product);

            $product = $this->repository->update($data,$product);

            return $this->respondWithItem($product);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Remove the specified user from db.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $product = $this->repository->byId($id);

            //authorize that a user can update the post he created
            $this->authorize('delete', $product);

            $this->repository->delete($product);

            return $this->respondOK('Deleted');
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
