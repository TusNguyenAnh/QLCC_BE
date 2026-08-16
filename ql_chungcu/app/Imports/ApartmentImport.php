<?php

namespace App\Imports;

use App\Models\Resident;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class ApartmentImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithStartRow
{
    protected $errors = [];

    public function __construct()
    {
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // Sẽ được xử lý trong service
    }

    public function startRow(): int
    {
        return 5;
    }

    /**
     * Chỉ định dòng tiêu đề là dòng 4
     */
    public function headingRow(): int
    {
        return 4;
    }

    /**
     * Validate từng dòng dữ liệu
     */
    public function validateRows(Collection $rows)
    {
        $this->errors = [];
        $validatedData = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + $this->startRow();
            $rowErrors = [];

            // Validate từng trường
            if (empty($row['toa_nha']) || trim($row['toa_nha']) === '') {
                $rowErrors[] = 'Tòa nhà không được để trống';
            }

            if (empty($row['so_can_ho']) || trim($row['so_can_ho']) === '') {
                $rowErrors[] = 'Số căn hộ không được để trống';
            }

            if (empty($row['tang']) || trim($row['tang']) === '') {
                $rowErrors[] = 'Số tầng không được để trống';
            }

            if (empty($row['dien_tich_tim_tuong']) || trim($row['dien_tich_tim_tuong']) === '') {
                $rowErrors[] = 'Diện tích tim tường không được để trống';
            }

            if (empty($row['he_so_quy_doi']) || trim($row['he_so_quy_doi']) === '') {
                $rowErrors[] = 'Hệ số quy đổi không được để trống';
            }

            if (empty($row['loai_can_ho']) || trim($row['loai_can_ho']) === '') {
                $rowErrors[] = 'Loại căn hộ không được để trống';
            }

            if (!empty($rowErrors)) {
                $this->errors[] = [
                    'row' => $rowNumber,
                    'errors' => $rowErrors,
                    'data' => $row->toArray()
                ];
            } else {
                // Chuẩn hóa dữ liệu
                $validatedData[] = [
                    'building_name' => trim($row['toa_nha']),
                    'apt_number' => trim($row['so_can_ho']),
                    'floor' => trim($row['tang']),
                    'gross_area' => trim($row['dien_tich_tim_tuong']),
                    'coefficient' => trim($row['he_so_quy_doi']),
                    'apt_type' => trim($row['loai_can_ho']),
                    'description' => trim($row['mo_ta']),
                ];
            }
        }

        return [
            'valid' => empty($this->errors),
            'data' => $validatedData,
            'errors' => $this->errors
        ];
    }

    /**
     * Xử lý lỗi validation
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values()
            ];
        }
    }

    /**
     * Lấy danh sách lỗi
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Kiểm tra có lỗi không
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }


}
