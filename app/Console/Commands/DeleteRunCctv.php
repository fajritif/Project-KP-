<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteRunCctv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $format = $this->argument('format');
        $folder = 'path/to/your/folder'; // Ganti dengan lokasi folder Anda

        $files = File::files($folder);

        foreach ($files as $file) {
            $extension = $file->getExtension();
            if ($extension === $format) {
                File::delete($file);
                $this->info("Deleted: " . $file);
            }
        }
    }
}
