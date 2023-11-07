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
        // $schedule->command('inspire')->hourly();
        
        $schedule->call(function () {
            $devices = Device::all();
            try {
                foreach ($devices as $device) {
                    if ($device->CAMERA_STREAMING) {
                        $kode = $device->KODE_DEVICE;
                        $escapedUrl = escapeshellarg($device->CAMERA_STREAMING);
                        $directoryPath = "D:\\Projects\\ptpn5\\millena-holding-web\\public\\streaming\\$kode";
                        $scriptDir = "IF NOT EXIST \"$directoryPath\" (mkdir \"$directoryPath\")";
                        $script = "ffmpeg -v verbose  -i $escapedUrl -vcodec libx264 -r 25 -b:v 1000000 -crf 31 -acodec aac  -sc_threshold 0 -f hls  -hls_time 5  -segment_time 5 -hls_list_size 5 D:\\Projects\\ptpn5\\millena-holding-web\\public\\streaming\\$kode\\stream.m3u8";
                        shell_exec("$scriptDir && start /B $script");
                    }
                }
            } catch (Exception $e) {
                Log::info($e);
            }
        })->everyMinute();
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
