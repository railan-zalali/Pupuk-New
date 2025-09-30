<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateValidationHelper
{
    /**
     * Validasi tanggal kedaluwarsa yang fleksibel
     * Mendukung berbagai format: YYYY-MM-DD, DD-MM-YYYY, DD/MM/YYYY, MM/DD/YYYY
     */
    public static function validateExpireDate($attribute, $value, $fail)
    {
        if ($value) {
            try {
                // Coba parse berbagai format tanggal
                $date = null;
                
                // Format YYYY-MM-DD
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                    $date = Carbon::createFromFormat('Y-m-d', $value);
                }
                // Format DD-MM-YYYY
                elseif (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value)) {
                    $date = Carbon::createFromFormat('d-m-Y', $value);
                }
                // Format DD/MM/YYYY
                elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
                    $date = Carbon::createFromFormat('d/m/Y', $value);
                }
                // Format MM/DD/YYYY
                elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
                    $date = Carbon::createFromFormat('m/d/Y', $value);
                }
                // Coba parse otomatis
                else {
                    $date = Carbon::parse($value);
                }
                
            } catch (\Exception $e) {
                $fail('Tanggal kedaluwarsa harus berupa format tanggal yang valid (contoh: 2024-12-31, 31-12-2024, atau 31/12/2024).');
            }
        }
    }

    /**
     * Validasi tanggal kedaluwarsa dengan pengecekan masa depan
     */
    public static function validateExpireDateAfterToday($attribute, $value, $fail)
    {
        if ($value) {
            try {
                // Coba parse berbagai format tanggal
                $date = null;
                
                // Format YYYY-MM-DD
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                    $date = Carbon::createFromFormat('Y-m-d', $value);
                }
                // Format DD-MM-YYYY
                elseif (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value)) {
                    $date = Carbon::createFromFormat('d-m-Y', $value);
                }
                // Format DD/MM/YYYY
                elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
                    $date = Carbon::createFromFormat('d/m/Y', $value);
                }
                // Format MM/DD/YYYY
                elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
                    $date = Carbon::createFromFormat('m/d/Y', $value);
                }
                // Coba parse otomatis
                else {
                    $date = Carbon::parse($value);
                }
                
                // Validasi tanggal harus setelah hari ini
                if ($date && $date->isPast()) {
                    $fail('Tanggal kedaluwarsa harus setelah hari ini.');
                }
                
            } catch (\Exception $e) {
                $fail('Tanggal kedaluwarsa harus berupa format tanggal yang valid (contoh: 2024-12-31, 31-12-2024, atau 31/12/2024).');
            }
        }
    }

    /**
     * Mendapatkan aturan validasi untuk tanggal kedaluwarsa
     */
    public static function getExpireDateRule($requireAfterToday = false)
    {
        return [
            'nullable',
            function ($attribute, $value, $fail) use ($requireAfterToday) {
                if ($requireAfterToday) {
                    self::validateExpireDateAfterToday($attribute, $value, $fail);
                } else {
                    self::validateExpireDate($attribute, $value, $fail);
                }
            }
        ];
    }
}