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

class ResidentImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithStartRow
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
            if (empty($row['ho_va_ten']) || trim($row['ho_va_ten']) === '') {
                $rowErrors[] = 'Họ và tên không được để trống';
            }

            if (empty($row['gioi_tinh']) || !in_array(strtolower(trim($row['gioi_tinh'])), ['nam', 'nu', 'nữ', 'male', 'female'])) {
                $rowErrors[] = 'Giới tính không hợp lệ (chỉ chấp nhận: Nam, Nữ, Male, Female)';
            }

            if (empty($row['email']) || !filter_var(trim($row['email']), FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = 'Email không hợp lệ';
            }

            if (empty($row['ngay_sinh'])) {
                $rowErrors[] = 'Ngày sinh không được để trống';
            } else {
                // Validate date format
                $birthday = $this->parseDate($row['ngay_sinh']);
                if (!$birthday) {
                    $rowErrors[] = 'Ngày sinh không đúng định dạng (dd/mm/yyyy hoặc yyyy-mm-dd)';
                }
            }

            if (empty($row['so_dien_thoai'])) {
                $rowErrors[] = 'Số điện thoại không được để trống';
            } elseif (!preg_match('/^[0-9]{10,11}$/', trim($row['so_dien_thoai']))) {
                $rowErrors[] = 'Số điện thoại phải có 10-11 chữ số';
            }

            if (empty($row['cccd'])) {
                $rowErrors[] = 'CCCD không được để trống';
            } elseif (!preg_match('/^[0-9]{9,12}$/', trim($row['cccd']))) {
                $rowErrors[] = 'CCCD phải có 9-12 chữ số';
            }

            if (empty($row['quan_he'])) {
                $rowErrors[] = 'Mối quan hệ không được để trống';
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
                    'id' => (string)Str::uuid(),
                    'complex_id' => jwt_claim('complex_id'),
                    'fullname' => trim($row['ho_va_ten']),
                    'gender' => $this->normalizeGender(trim($row['gioi_tinh'])),
                    'email' => trim($row['email']),
                    'birthday' => $this->parseDate($row['ngay_sinh']),
                    'phone_number' => trim($row['so_dien_thoai']),
                    'cccd' => trim($row['cccd']),
                    'relationship' => trim($row['quan_he']),
                    'status' => 0,
                    'created_at' => Date::now(),
                    'updated_at' => Date::now()
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

    /**
     * Parse date từ nhiều định dạng
     */
    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        // Nếu là số (Excel date serial number)
        if (is_numeric($date)) {
            try {
                $datetime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date);
                return $datetime->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // Thử parse các định dạng thông dụng
        $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y'];
        foreach ($formats as $format) {
            $parsed = \DateTime::createFromFormat($format, trim($date));
            if ($parsed !== false) {
                return $parsed->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Chuẩn hóa giới tính
     */
    private function normalizeGender($gender)
    {
        $gender = strtolower(trim($gender));
        $maleValues = ['nam', 'male', 'm'];
        $femaleValues = ['nu', 'nữ', 'female', 'f'];

        if (in_array($gender, $maleValues)) {
            return 0;
        } elseif (in_array($gender, $femaleValues)) {
            return 1;
        }

        return $gender;
    }
}
