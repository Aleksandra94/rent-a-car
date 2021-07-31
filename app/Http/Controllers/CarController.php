<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use  App\Models\Car;
use  App\Models\Category;
use  App\Models\Reservation;
use  App\Mail\SendMailable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class CarController extends Controller
{
  
    public function index()
    {
      return response()->json(['Wellcome'], 200);
    }
    public function list(Request $request)
    {
      
      $orderByArray = $request->input("order-by");
      if(empty($orderByArray))
               return DB::table('car')->paginate(5);
      else {
        $cars = [];
        $orderArray = explode(",", $orderByArray);
        $orderCount = count($orderArray);
        $query = DB::table('car');
       foreach ($orderArray as $order) {
                $query->orderBy($order,'asc');
       }
       $cars = $query->paginate(5);
        return response()->json([$cars], 200);
      }
    }
   
    public function store()
    {
       request()->validate([
          'registration_licence'=>'required',
          'brand' => 'required',
          'model' => 'required',
          'manufacture_date' => 'required',
          'category_id' => 'required',
        ]);
        return Car::create([
            'registration_licence' => request('registration_licence'),
            'brand' => request('brand'),
            'model' => request('model'),
            'manufacture_date' => request('manufacture_date'),
            'car_description' => request('car_description'),
            'properties' => request('properties'),
            'category_id' => request('category_id'),
            'slug'=> Str::slug(request('registration_licence') .' '. request('brand') .' '. request('model')),
        ]);
        return response()->json(["message" => "Car created"], 201);
    }
   
    public function update(Car $car)
    {
        request()->validate([
          'registration_licence' => 'required',
          'brand' => 'required',
          'model' => 'required',
          'manufacture_date' => 'required',
          'category_id' => 'required',
        ]);

        $success = $car->update([
          'registration_licence' => request('registration_licence'),
          'brand' => request('brand'),
          'model' => request('model'),
          'manufacture_date' => request('manufacture_date'),
          'car_description' => request('modcar_description'),
          'properties' => request('properties'),
          'category_id' => request('category_id'),

        ]);

        return [
            'success' => $success
        ];
    }
    public function destroy(Car $car)
    {
        $success = $car->delete();

        return [
            'success' => $success
        ];
    }
    public function getSlug(Car $car)
    {
        return $car;
    }
    public function search(Request $request)
    {
        $includeAll = $request->input("include-all");
        $model = $request->input("model");
        $brand = $request->input("brand");
        $priceRange = $request->input("price");
        $category = $request->input("category");
        $cars=[];
        if($includeAll == "true"){
          if(!empty($priceRange))
          {
              $categories = Category::whereBetween('price',  [explode("-", $priceRange)[0], explode("-", $priceRange)[1]])
                      ->where('name', 'like', "%{$category}%") 
                     ->get()->pluck('id');
            
               $cars = Car::where('brand', 'like', "%{$brand}%")
                    ->where('model', 'like', "%{$model}%")
                    ->whereIn('category_id', $categories)
                    ->get();
          }
          else {
               $categories = Category::where('name', 'like', "%{$category}%") 
                    ->get()->pluck('id');
              $cars = Car::where('brand', 'like', "%{$brand}%")
                    ->where('model', 'like', "%{$model}%")
                    ->whereIn('category_id', $categories)
                    ->get();
          }
  
        }
        else{
          if(!empty($priceRange))
          {
              $categories = Category::whereBetween('price',  [explode("-", $priceRange)[0], explode("-", $priceRange)[1]])
                    ->orWhere('name', 'like', "%{$category}%")
                    ->get()->pluck('id');
            
              $cars = Car::orWhere('brand', 'like', "%{$brand}%")
                    ->orWhere('model', 'like', "%{$model}%")
                    ->orWhereIn('category_id', $categories)
                    ->get();
          }else {
                $categories = Category::where('name', 'like', "%{$category}%") 
                    ->get()->pluck('id');
               $cars = Car::orWhere('brand', 'like', "%{$brand}%")
                    ->orWhere('model', 'like', "%{$model}%")
                     ->orWhereIn('category_id', $categories)
                    ->get();
                 }
         }
       
        if($cars->isEmpty())
                return response()->json(["message" => "No search results. Try turning off include all filters."], 200);
        else
                return response()->json([$cars], 200);
    }

    public function reservation(Request $request)
    {
      $registration_licence = ($request->registration_licence);
      $category = ($request->category);
      $date_range = ($request->date_range);
      $date_diff =  date_diff(date_create(explode(":", $date_range)[0]),date_create(explode(":", $date_range)[1])); 
      $result = '';
      if(!empty($registration_licence))
      {
        $car = Car::where('registration_licence', 'like', "%{$registration_licence}%")
                    ->first();
        $reserved = Reservation::where('car_id', 'like', "%{$car['id']}%")
                    ->whereDate('reserved_from', '<=' , explode(":", $date_range)[1])
                    ->whereDate('reserved_to', '>=' , explode(":", $date_range)[0])
                    ->get();
        $cat=Category::where('id', 'like', "%{$car['category_id']}%")->first();
        $reservation_id=Reservation::where('id')->first();

        if(!$reserved->isEmpty()){
          $result = response()->json([
            "registration_licence" => $car['registration_licence'],
            "category" => $cat->name,
            "status" => "FAILED. Car is already reserved.",
            "requested_date_range" => $date_range,
            "total_price" => $cat->price * $date_diff->days
          ], 200);
        }
        else
        {      
          DB::insert('insert into reservation (id, reserved_from, reserved_to, car_id) values (?, ?, ?, ?)', [$reservation_id, explode(":", $date_range)[0], explode(":", $date_range)[1],$car['id']]);
         
          $result = response()->json([
            "registration_licence" => $car['registration_licence'],
            "category" => $cat->name,
            "status" => "SUCCESS",
            "requested_date_range" => $date_range,
            "total_price" =>  $cat->price * $date_diff->days
          ], 200);
        }
      }
      else if(!empty($category))
      {
        $category = Category::where('name', 'like', "%{$category}%")->first();//->pluck('id');
        $cars = Car::where('category_id', 'like', "%{$category['id']}%")->get();
        foreach($cars as $car)
        {
          $reserved = Reservation::where('car_id', 'like', "%{$car['id']}%")
          ->where('reserved_from', '<=' , explode(":", $date_range)[1])
          ->where('reserved_to', '>=' , explode(":", $date_range)[0])
          ->get();
          if($reserved->isEmpty()){
            DB::insert('insert into reservation (id, reserved_from, reserved_to, car_id) values (?, ?, ?, ?)', [10, explode(":", $date_range)[0], explode(":", $date_range)[1],$car['id']]);
            $result = response()->json([
              "registration_licence" => $car['registration_licence'],
              "category" => $category->name,
              "status" => "SUCCESS",
              "requested_date_range" => $date_range,
              "total_price" => $category->price * $date_diff->days
            ], 200);
            $this->mail($result);
            return $result;
          }
        }
        $result = response()->json([
          "registration_licence" => $cars->first()->registration_licence,
          "category" => $category->name,
          "status" => "FAILED. Car is already reserved.",
          "requested_date_range" => $date_range,
          "total_price" => $category->price * $date_diff->days
        ], 200);
      }
      else{
        $result = response()->json(["message" => "You have to insert registration licence or car category."], 200);
      }
      $this->mail($result);
      return $result;
    }

    public function mail($data)
    {
        Mail::to('rentacarcompany11@gmail.com')->send(new SendMailable($data));
          return 'Email sent Successfully';
    }

}
