<?php

use Illuminate\Support\Facades\Schedule;

// Perintah ini akan menjalankan 'send:reminder-kontrol' setiap hari jam 09:00 pagi
Schedule::command('send:reminder-kontrol')->dailyAt('09:00');
