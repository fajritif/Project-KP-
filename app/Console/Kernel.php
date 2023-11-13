<?php

namespace App\Console;

use App\Models\Device;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {


        $schedule->call(function () {

            // Tentukan nama folder yang ingin dibuat di dalam folder storage
            $folderName = 'streaming';

            // Tentukan path lengkap ke folder yang akan dibuat
            $folderPath = storage_path("app/{$folderName}");

            // Cek apakah folder sudah ada, jika belum buat folder baru
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true, true);
            }

            // Tentukan path folder yang ingin dihapus isinya
            $directoryPath = storage_path('app/streaming');

            // Hapus semua file dan direktori di dalam folder
            File::cleanDirectory($directoryPath);
            $devices = Device::whereNotNull("CAMERA_STREAMING")->get();

            try {
                $commands = [];

                foreach ($devices as $device) {
                    if ($device->CAMERA_STREAMING) {
                        $kode = $device->KODE_DEVICE;
                        $escapedUrl = escapeshellarg($device->CAMERA_STREAMING);
                        $directoryPath = "C:/laragon/www/millena-holding-web/storage/app/streaming/$kode";
                        $scriptDir = "IF NOT EXIST \"$directoryPath\" (mkdir \"$directoryPath\")";
                        $script = "ffmpeg -v verbose -rtsp_transport tcp -i $escapedUrl -vcodec libx264 -r 25 -b:v 1000k -crf 23 -acodec aac -b:a 128k -sc_threshold 0 -f hls -hls_time 5 -segment_time 5 -hls_list_size 5 -hls_flags delete_segments C:/laragon/www/millena-holding-web/storage/app/streaming/$kode/stream.m3u8";
                        $commands[] = "$scriptDir && start /B $script";
                    }
                }

                // Gabungkan semua perintah menjadi satu
                $fullScript = implode(' && ', $commands);

                // Jalankan semua perintah sebagai satu proses latar belakang
                shell_exec("$fullScript &");
            } catch (Exception $e) {
                Log::info($e);
            }
        })->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
