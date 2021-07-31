<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use  App\Models\Category;

use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function index()
    {
        
    }

    public function list(Request $request)
    {
        $currency = $request->input("currency");
        $categories =  Category::all();
        if(!empty($currency))
        {
            foreach($categories as $category)
            {
                $category['price'] = $this->exchangeCurrency($category['price'], $currency);
            }
        }
        else
        {
            foreach($categories as $category)
            {
                $category['price'] = "{$category['price']} USD";
            }
        }
        return $categories;
    }

    public function store()
    {
        request()->validate([
          'name' => 'required',
          'price' => 'required',
          'parent_id' => 'required',
        ]);

        return Category::create([
            'name' => request('name'),
            'price' => request('price'),
            'parent_id' => request('parent_id'),

        ]);
    }

    public function update(Category $category)
    {
        request()->validate([
            'name' => 'required',
            'price' => 'required',
            'parent_id' => 'required',
        ]);

        $success = $category->update([
            'name' => request('name'),
            'price' => request('price'),
            'parent_id' => request('parent_id'),

        ]);

        return [
            'success' => $success
        ];
    }

    public function destroy(Category $category)
    {
        $success = $category->delete();

        return [
            'success' => $success
        ];
    }

    function exchangeCurrency($amountC, $toC) {
      
        $amount = $amountC?$amountC:(1);
   
        $apikey = 'c8983dcc2e08f78cdc08';
      //  $from = urlencode($request->from);
        $to = urlencode($toC);
        $query =  "USD_{$to}";
        $json = file_get_contents("https://free.currconv.com/api/v7/convert?q=USD_{$to}&compact=ultra&apiKey={$apikey}");
      //echo $json;
        $obj = json_decode($json, true);
        $val = $obj["$query"];
        $total = $val * $amount;
   
        $formatValue = number_format($total, 2, '.', '');
         
        $data = "$formatValue $to";
   
        return $data;
   
     }
}
    
