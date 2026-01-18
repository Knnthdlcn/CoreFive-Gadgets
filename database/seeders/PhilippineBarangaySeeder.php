<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhilippineBarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('philippine_barangays')->count()) {
            return;
        }

        $path = __DIR__ . '/sql/philippine_barangays.sql';
        if (!is_file($path)) {
            throw new \RuntimeException('Missing seed file: ' . $path);
        }

        // The source file contains one INSERT statement per line (~42k lines).
        // Chunk execution keeps memory usage low and avoids timeouts on slower environments.
        $file = new \SplFileObject($path);

        $buffer = '';
        $batchSize = 250;
        $linesInBatch = 0;

        while (!$file->eof()) {
            $line = trim($file->fgets());
            if ($line === '') {
                continue;
            }

            $buffer .= $line . "\n";
            $linesInBatch++;

            if ($linesInBatch >= $batchSize) {
                DB::unprepared($buffer);
                $buffer = '';
                $linesInBatch = 0;
            }
        }

        if ($buffer !== '') {
            DB::unprepared($buffer);
        }
    }
}
