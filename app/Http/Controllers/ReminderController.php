<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ReminderController extends Controller
{
    public function kirimManual()
    {
        try {
            // Memanggil perintah artisan yang sudah kita buat tadi
            Artisan::call('send:reminder-kontrol');
            
            return back()->with('success', 'Reminder WhatsApp sedang diproses!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim: ' . $e->getMessage());
        }
    }
}