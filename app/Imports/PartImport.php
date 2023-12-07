<?php

namespace App\Imports;

use App\Models\Part;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PartImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Convert values to uppercase
        $partNumber = strtoupper($row['part_number']);
        $partName = strtoupper($row['part_name']);
        $customer = strtoupper($row['customer']);

        if (empty($partName) || empty($customer)) {
            throw new \Exception('Part Name dan Customer Tidak Boleh Kosong');
        }

        $customerModel = Customer::where('name', $customer)->first();

        if (!$customerModel) {
            throw new \Exception('Customer ' . $customer . ' Tidak Ditemukan');
        }

        $existingPart = Part::where('part_name', $partName)->first();

        if ($existingPart) {
            $existingPart->update([
                'part_number' => $partNumber,
                'customer_id' => $customerModel->id,
                'updated_at' => now(),
            ]);

            return $existingPart;
        } else {
            $newPart = new Part([
                'part_number' => $partNumber,
                'part_name' => $partName,
                'customer_id' => $customerModel->id,
                'created_by' => Auth::user()->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $newPart->save();
            return $newPart;
        }
    }
}


