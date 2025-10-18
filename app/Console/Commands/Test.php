<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description - test';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//      $response = Http::post('https://reqres.in/api/users?page=2');
//      dd($response->json());

//        Vise ne radi - izbacuje 401 gresku

        $response = Http::post('https://reqres.in/api/users/create', [
            "name" => "Test",
            "job" => "Test",
        ]);
        dd($response->status());
    }
}
