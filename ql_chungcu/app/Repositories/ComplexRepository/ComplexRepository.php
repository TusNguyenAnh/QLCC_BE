<?php

namespace App\Repositories\ComplexRepository;

use App\Models\Complex;
use Illuminate\Support\Facades\DB;

class ComplexRepository implements IComplexRepository
{

    public function show($filters, $status, $perPage)
    {
        $query = Complex::where('status', $status);

        $query->when($filters['time_request_start'] ?? null,
            fn($q, $v) => $q->whereDate('created_at', '>=', $v)
        );

        $query->when($filters['time_request_end'] ?? null,
            fn($q, $v) => $q->whereDate('created_at', '<=', $v)
        );

        $query->when($filters['keyword'] ?? null, function ($q, $v) {
            $q->where(function ($sub) use ($v) {
                $sub->where('complex_name', 'like', "%$v%")
                    ->orWhere('address', 'like', "%$v%")
                    ->orWhere('name_contact', 'like', "%$v%")
                    ->orWhere('phone_contact', 'like', "%$v%");
            });
        });

        // Order
        $order = strtolower($filters['order'] ?? 'desc');
        $order = in_array($order, ['asc', 'desc']) ? $order : 'desc';
        $query->orderBy('created_at', $order);

        return $query->paginate($perPage);

    }

    public function getById(string $id)
    {
        $complex = Complex::where('id', $id)->first();
        return $complex;
    }

    public function store(array $data)
    {
        $complex = Complex::create($data)->fresh();
        return $complex;
    }

    public function update(array $data, string $id)
    {
        $complex = Complex::where('id', $id)->first();
        if (!$complex) return null;

        $complex->update($data);
        return $complex->fresh();
    }

    public function delete(array $listCpl)
    {
        return Complex::withTrashed()
            ->whereIn('id', $listCpl)
            ->forceDelete();
    }

    public function findByComplexName(string $complexName)
    {
        return Complex::where('complex_name', $complexName)
            ->whereNull('deleted_at')
            ->first();
    }

    public function findByComplexAddress(string $complexAddress)
    {
        return Complex::where('address', $complexAddress)
            ->whereNull('deleted_at')
            ->first();
    }

    public function approveComplex(array $listCpl)
    {
        return DB::transaction(function () use ($listCpl) {
            return Complex::whereIn('id', $listCpl)->update([
                'status' => '1'
            ]);
        });

    }

    public function findByComplexPhoneContact(string $phoneContact)
    {
        return Complex::where('phone_contact', $phoneContact)
            ->whereNull('deleted_at')
            ->first();
    }
}
