<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // First check if the foreign key exists and drop it
        $foreignKeys = DB::select("SHOW CREATE TABLE pickups");
        $createTable = $foreignKeys[0]->{'Create Table'} ?? '';
        
        if (strpos($createTable, 'pickups_requested_by_foreign') !== false) {
            DB::statement('ALTER TABLE pickups DROP FOREIGN KEY pickups_requested_by_foreign');
        }
        
        // Add new foreign key that references people table
        DB::statement('ALTER TABLE pickups ADD CONSTRAINT pickups_requested_by_foreign FOREIGN KEY (requested_by) REFERENCES people(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down(): void
    {
        // Drop the new foreign key
        DB::statement('ALTER TABLE pickups DROP FOREIGN KEY pickups_requested_by_foreign');
        
        // Restore the old foreign key to users table
        DB::statement('ALTER TABLE pickups ADD CONSTRAINT pickups_requested_by_foreign FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }
};
