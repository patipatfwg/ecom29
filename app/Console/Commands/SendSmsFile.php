<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduleSms;
use Dotenv\Dotenv;
use phpseclib\Net\SFTP;
use Exception;

class SendSmsFile extends Command
{
    private $statues = [
        'active'   => 'active',
        'inactive' => 'inactive',
        'delete'   => 'delete',
    ];
    private $messages;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:sms {--datetime=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send sms batch file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->messages = config('message.upload_sms_file');
    }

    /**
    * Method for get date time from input default is current datetime
    **/
    private function getDatetimeFromInput()
    {
        $datetime = date("Y-m-d H:i:s");
        //get all input 
        $inputs   = $this->option();
        
        if (isset($inputs['datetime']) && !empty($inputs['datetime'])) {
            $datetime = $inputs['datetime'];
        }
        return $datetime;
    }

    /**
    * Method for update status in schedule record
    **/
    private function updateScheduleRecord($id)
    {
        $schedule = ScheduleSms::find($id);
        $schedule->status = $this->statues['delete'];

        $schedule->save();
    }

    /**
    * Method for get data from schedule collection by datetime
    **/
    private function getScheduleData($datetime)
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => ''
        ];
        //Connect with model
        $schedules = ScheduleSms::where('status', $this->statues['active'])->where('schedule_datetime', '<=', $datetime)->get()->toArray();

        if (!empty($schedules)) {
            $outputs['data'] = $schedules;
        } else {
            $outputs['success'] = false;
            $outputs['message'] = $this->messages['noData'];
        }


        return $outputs;
    }

    /**
    * Method for replace service name in destination
    **/
    private function getDestinationPath($schedule)
    {
        $dest = config('uploadfile.dest_path');
        return str_replace("[service]", $schedule["service_name"], $dest);
    }

    /**
    * Method for connect sftp server
    **/
    private function connectSFTPServer()
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        //get config
        $sftpConfig = config('uploadfile.sftp');

        try {
            $sftp = new SFTP($sftpConfig['host']. ':' . $sftpConfig['port']);

            if (!$sftp->login($sftpConfig['user'], $sftpConfig['pass'])) {
                $outputs['success'] = false;
                $outputs['message'] = $this->messages['authenFail'];
            }
            $outputs['resSFTP'] = $sftp; 

        } catch (Exception $e) {
            $outputs['success'] = false;
            $outputs['message'] = $this->messages['cannotConnect'];
        }
        return $outputs;
    }

    /**
    * Method for send schedule file to sms server
    **/
    private function sendBatchFileToServer($schedules)
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];
        
        //get connection
        $res = $this->connectSFTPServer();
        if (!$res['success']) {
            return $res;
        }

        $sftp = $res['resSFTP'];

        try {

            foreach ($schedules as $schedule) {
                //get destination path
                $destPath     = $this->getDestinationPath($schedule);
                $srcFile      = file_get_contents(config('uploadfile.path_upload').$schedule['file_name']);
                $sftp->put($destPath.$schedule['file_name'], $srcFile);

                //update status in schedule record
                $this->updateScheduleRecord($schedule['_id']);
            }
            

        } catch (\Exception $e) {
            $outputs['success'] = false;
            $outputs['message'] = $this->messages['uploadFail'];
        } 

        //Connect with model
        return $outputs;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //get datetime
        $datetime  = $this->getDatetimeFromInput();
        
        //get data from schedule_sms collection
        $schedules = $this->getScheduleData($datetime);

        $this->info("Get schedule data");
        if (!$schedules['success']) {
            $this->error($schedules['message']);
            return ;
        }

        $this->info("Send file to server");
        //Send all file to server
        $result = $this->sendBatchFileToServer($schedules['data']);

        if (!$result['success']) {
            $this->error($result['message']);
            return ;
        }

        $this->info("Upload file successfuly");
    }
}
