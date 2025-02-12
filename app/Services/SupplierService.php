<?php

namespace App\Services;

use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Interfaces\SupplierInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\SupplierResource;
use App\Models\Suppliers;

class SupplierService implements SupplierInterface
{
    public function getAllSupplier()
    {
        $Supplier = Suppliers::paginate(10);
        return [
            'data' => SupplierResource::collection($Supplier)->resolve(),
            'pagination' => [
                'per_page' => $Supplier->perPage(),
                'total' => $Supplier->total(),
                'current_page' => $Supplier->currentPage(),
                'last_page' => $Supplier->lastPage(),
            ]
        ];
    }

    public function createSupplier(CreateSupplierRequest $request)
    {

        $Supplier = Suppliers::select('id', 'name', 'email')
            ->where('email', $request->email)
            ->first();

        if ($Supplier) {
            return ApiResponseClass::throw(__('messages.supplier_exist'), 409);
        }

        try {
            $Supplier = Suppliers::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'address' => $request->address,
                'status' => $request->status,
                'company_name' => $request->company_name,
                'date_of_joining' => $request->date_of_joining
            ]);

            return new SupplierResource($Supplier);

        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }


        return new SupplierResource($Supplier);
    }

    public function updateSupplier(UpdateSupplierRequest $request, $id)
    {

        $Supplier = Suppliers::where('id',$id)->first();
        if (!$Supplier) {
            return ApiResponseClass::throw(__('messages.user_not_found'), 404);
        }
      
        try {
            $Supplier->update($request->validated());
            return new SupplierResource($Supplier);
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function deleteSupplier($id)
    {
        $Supplier = Suppliers::where('id',$id)->first();

        if(!$Supplier) {
            return ApiResponseClass::throw(__('messages.user_not_found'), 404);
        }

        $Supplier->delete();
        return ApiResponseClass::sendResponse(null, __('messages.supplier_deleted'), 200);
    }
}
