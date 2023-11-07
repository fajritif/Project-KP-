<?php

namespace App\Console\Commands;

use App\Models\Device;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunCctvStream extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cctv:run';

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
        $devices = Device::whereNotNull("CAMERA_STREAMING")->get();
            try {
                foreach ($devices as $device) {
                    if ($device->CAMERA_STREAMING) {
                        $kode = $device->KODE_DEVICE;
                        $escapedUrl = escapeshellarg($device->CAMERA_STREAMING);
                        $directoryPath = "D:\\Projects\\ptpn5\\millena-holding-web\\public\\streaming\\$kode";
                        $scriptDir = "IF NOT EXIST \"$directoryPath\" (mkdir \"$directoryPath\")";
                        $script = "ffmpeg -v verbose  -i $escapedUrl -vcodec libx264 -r 25 -b:v 1000000 -crf 31 -acodec aac  -sc_threshold 0 -f hls  -hls_time 5  -segment_time 5 -hls_list_size 5 D:\\Projects\\ptpn5\\millena-holding-web\\public\\streaming\\$kode\\stream.m3u8";
                        // $schedule->exec("")
                        shell_exec("$scriptDir && start /B $script");
                    }
                }
            } catch (Exception $e) {
                Log::info($e);
            }
    }
}
