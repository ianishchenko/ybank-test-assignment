<?php

use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transactions')->insert([
            'from' => 1,
            'to' => 2,
            'details' => 'sample transaction',
            'amount' => 24,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'current_currency' => 0.9202171713
        ]);

        DB::table('transactions')->insert([
            'from' => 1,
            'to' => 2,
            'details' => 'sample transaction 2',
            'amount' => 24,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'current_currency' => 0.94
        ]);

        DB::table('transactions')->insert([
            'from' => 2,
            'to' => 1,
            'details' => 'sample transaction 3',
            'amount' => 15,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'current_currency' => 0.9202171713
        ]);
    }
}
