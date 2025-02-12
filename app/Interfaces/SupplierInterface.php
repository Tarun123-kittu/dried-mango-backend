<?php

namespace App\Interfaces;
use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;

interface SupplierInterface
{
    public function getAllSupplier();
    public function createSupplier(CreateSupplierRequest $request);
    public function updateSupplier(UpdateSupplierRequest $request, $id);
    public function deleteSupplier($id);
}
