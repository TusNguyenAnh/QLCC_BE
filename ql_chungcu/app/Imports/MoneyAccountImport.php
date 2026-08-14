<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Validators\Failure;

class MoneyAccountImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithStartRow
{
    protected $errors = [];

    public function __construct() {}

    /**
     * Đọc dữ liệu từ collection (xử lý trong service)
     */
    public function collection(Collection $collection) {}

    /**
     * Dữ liệu bắt đầu từ dòng 5
     */
    public function startRow(): int
    {
        return 5;
    }

    /**
     * Tiêu đề header ở dòng 4
     */
    public function headingRow(): int
    {
        return 4;
    }

    /**
     * Validate tất cả các dòng dữ liệu
     */
    public function validateRows(Collection $rows)
    {
        $this->errors = [];
        $validatedData = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + $this->startRow();
            $rowErrors = [];

            // Validate từng trường
            if (empty($row['ngan_hang']) || trim($row['ngan_hang']) === '') {
                $rowErrors[] = 'Ngân hàng không được để trống';
            }

            if (empty($row['stk/hdtg']) || trim($row['stk/hdtg']) === '') {
                $rowErrors[] = 'STK/HĐTG không được để trống';
            }

            if (empty($row['ky_han']) || trim($row['ky_han']) === '') {
                $rowErrors[] = 'Kỳ hạn không được để trống';
            }

            if (empty($row['ngay_gui']) || trim($row['ngay_gui']) === '') {
                $rowErrors[] = 'Ngày gửi không được để trống';
            }

            if (empty($row['den_han']) || trim($row['den_han']) === '') {
                $rowErrors[] = 'Ngày đến hạn không được để trống';
            }

            if (empty($row['lai_suat/nam']) || trim($row['lai_suat/nam']) === '') {
                $rowErrors[] = 'Lãi suất không được để trống';
            }

            if (empty($row['so_tien']) || trim($row['so_tien']) === '') {
                $rowErrors[] = 'Số tiền không được để trống';
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
                    'bank_name' => trim($row['ngan_hang']),
                    'account_number' => trim($row['stk/hdtg']),
                    'term' => trim($row['ky_han']),
                    'deposit_date' => trim($row['ngay_gui']),
                    'maturity_date' => trim($row['den_han']),
                    'interest_rate' => trim($row['lai_suat/nam']),
                    'money' => trim($row['so_tien']),
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
     * Xử lý lỗi validation từ Maatwebsite
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
