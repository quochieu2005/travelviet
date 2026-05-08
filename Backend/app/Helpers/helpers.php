<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

if (!function_exists('vn_encrypt')) {
    /**
     * Hàm mã hóa dữ liệu (Mật khẩu, đường dẫn, ID...)
     */
    function vn_encrypt($value)
    {
        return Crypt::encryptString($value);
    }
}

if (!function_exists('vn_decrypt')) {
    /**
     * Hàm giải mã dữ liệu
     */
    function vn_decrypt($payload)
    {
        try {
            return Crypt::decryptString($payload);
        } catch (DecryptException $e) {
            // Trả về null hoặc báo lỗi nếu dữ liệu bị giả mạo hoặc key sai
            return null; 
        }
    }
}