<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PratitaRunNow extends Command
{
    // Ini nama perintah kustom yang Anda inginkan
    protected $signature = 'pratita:run-now'; 

    // Deskripsi singkat perintah Anda
    protected $description = 'Menjalankan server lokal menggunakan nama Pratita';

    public function handle()
    {
        $this->info('Memulai server Pratita...');
        
        // Memanggil perintah bawaan php artisan serve
        $this->call('serve'); 
    }
}