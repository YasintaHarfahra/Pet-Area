<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format angka ke format Rupiah
     * 
     * @param int|float $amount Nilai dalam Rupiah
     * @param bool $withPrefix Tambahkan prefix 'Rp'
     * @return string
     * 
     * Contoh:
     * formatRupiah(150000) => "Rp150.000"
     * formatRupiah(1500000) => "Rp1.500.000"
     * formatRupiah(150000, false) => "150.000"
     */
    public static function formatRupiah($amount, $withPrefix = true)
    {
        $formatted = number_format($amount ?? 0, 0, ',', '.');
        return $withPrefix ? 'Rp' . $formatted : $formatted;
    }

    /**
     * Format angka ke format currency dengan desimal
     */
    public static function formatCurrency($amount, $decimals = 2)
    {
        return number_format($amount ?? 0, $decimals, ',', '.');
    }

    /**
     * Parse string Rupiah ke integer
     * Contoh: "Rp150.000" => 150000
     */
    public static function parseRupiah($value)
    {
        // Hapus prefix Rp dan spasi
        $value = str_replace(['Rp', ' '], '', $value);
        // Hapus separator ribuan
        $value = str_replace('.', '', $value);
        // Ganti desimal comma dengan dot
        $value = str_replace(',', '.', $value);
        return (int) floatval($value);
    }
}
