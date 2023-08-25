<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->insert([
            ['id' => Str::uuid(),'name' => 'Recebimento de Salario'],
            ['id' => Str::uuid(),'name' => 'Casa'],
            ['id' => Str::uuid(),'name' => 'Alimentação'],
            ['id' => Str::uuid(),'name' => 'Educação'],
            ['id' => Str::uuid(),'name' => 'Lazer'],
            ['id' => Str::uuid(),'name' => 'Roupas'],
            ['id' => Str::uuid(),'name' => 'Transporte'],
            ['id' => Str::uuid(),'name' => 'Viagem'],
            ['id' => Str::uuid(),'name' => 'Cartão'],
            ['id' => Str::uuid(),'name' => 'Outro'],
        ]);
    }
}
