<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::delete("DELETE FROM users WHERE id = 1");
        DB::unprepared("INSERT INTO `users` (id, name, email, password, is_admin)
		  VALUES
                (1, 'admin', 'root@localhost.net', '" . Hash::make('password') . "', 1)            
		  ON DUPLICATE KEY UPDATE 
		  id=VALUES(`id`), name=VALUES(`name`), email=VALUES(`email`), password=VALUES(`password`), is_admin=VALUES(`is_admin`)");

    }
}
