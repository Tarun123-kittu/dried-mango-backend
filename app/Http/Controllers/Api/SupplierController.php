<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Interfaces\SupplierInterface;
use App\Models\Suppliers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupplierController extends Controller
{
    protected $SupplierService;

    public function __construct(SupplierInterface $SupplierService)
    {
        $this->SupplierService = $SupplierService;
    }

    public function index()
    {
        try {
            $Supplier = $this->SupplierService->getAllSupplier();
            return ApiResponseClass::sendResponse($Supplier, __('messages.supplier_listed'), 200);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function store(CreateSupplierRequest $request)
    {
        DB::beginTransaction();
        try {
            $Supplier = $this->SupplierService->createSupplier($request);
            DB::commit();
            return ApiResponseClass::sendResponse($Supplier, __('messages.supplier_created'), 201);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function update(UpdateSupplierRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $Supplier = $this->SupplierService->updateSupplier($request, $id);
            return $Supplier;
            DB::commit();
            return ApiResponseClass::sendResponse($Supplier, __('messages.supplier_updated'), 200);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $Supplier = $this->SupplierService->deleteSupplier($id);
            DB::commit();
            return ApiResponseClass::sendResponse(null, __('messages.supplier_deleted'), 200);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return $e->getMessage();
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function show($id)
    {
        try {
            $Supplier = Suppliers::where('id', $id)->first();

            if (!$Supplier) {
                return ApiResponseClass::throw(__('messages.user_not_found'), 404);
            }

            return ApiResponseClass::sendResponse(new SupplierResource($Supplier), __('messages.supplier_fetched'), 200);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }
}
