<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use  App\Models\Reservation;
use Carbon\Carbon;
use Response; 
use  App\Mail\SendMailable;
use Illuminate\Support\Facades\Mail;

class ExportingAllReservedCars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reserved:cars';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporting all reserved cars for the current week';

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
       
      $headers = array(
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=reserved_cars.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
      );
      $result=Reservation::where('reserved_from', '<', Carbon::now()->endOfWeek())
                            ->where('reserved_to', '>', Carbon::now()->startOfWeek())
                            ->get();
                            
      echo $result;

      $file =  fopen('file-test.csv', 'w');
       
      foreach($result as $r) {
            fputcsv($file, array(
                    $r->id,
                    $r->reserved_from,
                    $r->reserved_to,
                    $r->car_id,
                ));
	
	    }
	    fclose($file);
      $handle = fopen('file-test.csv', "r");
      $result =   'ID ReservedFrom ResrvedTo CarID'."\r\n";
     // echo $result;
      while ($csvLine = fgetcsv($handle, 1000, ",")) {
          $tmp =  $csvLine[0] ." ".  $csvLine[1] ." ".  $csvLine[2] ." ".  $csvLine[3];
          $result = $result."\r\n". $tmp;
      }
      Mail::to('rentacarcompany11@gmail.com')->send(new SendMailable($result));

    }
}
