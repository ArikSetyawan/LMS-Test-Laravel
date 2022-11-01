<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spko;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Spko_item;

class SpkoController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $employees = Employee::all();
        $spkos = Spko::with('employees')->get();
        $process = ['Cor','Brush','Bombling','Slep'];
        return view('spko', ['spkos'=>$spkos, 'products'=>$products, 'employees'=>$employees, 'process'=>$process]);
    }
    
    public function detailSpko($id_spko)
    {
        $employees = Employee::all();
        $spko = Spko::with('employees','spko_items.products')->where('id_spko',$id_spko)->get();
        // dd($spko->all());
        return view('detail_spko', ['spko'=>$spko[0], 'employees'=>$employees]);
    }

    public function detailSpkoPrint($id_spko)
    {
        $employees = Employee::all();
        $spko = Spko::with('employees','spko_items.products')->where('id_spko',$id_spko)->get();
        // dd($spko->all());
        return view('print_detail_spko', ['spko'=>$spko[0], 'employees'=>$employees]);
    }

    public function createSpko(Request $request)
    {
        $params = $request->all();
        $items = array();
        foreach ($params as $key =>$value) {
            if (strpos($key, 'product_') !== false){
                $id_product = explode("_",$key);
                $product = [$id_product[1]=>$value];
                array_push($items,$product);
            }
        }
        if (count($items) == 0) {
            return redirect('/');
        }
        $sw = "SPKO" . date("y") . date("m") . rand(100,999);

        $date_now = date("Y-m-d");
        // create spko
        $spko = new Spko;
        $spko['remarks'] = $request['remarks'];
        $spko['employee'] = $request['employee'];
        $spko['trans_date'] = $date_now;
        $spko['process'] = $request['process'];
        $spko['sw'] = $sw;
        $spko->save();

        // get current spko id
        $id_spko = Spko::where('sw',$sw)->first();
        $id_spko = $id_spko['id_spko'];
        
        // Insert items in spko_items
        foreach ($items as $item) {
            foreach ($item as $id_product => $qty) {
                $spko_item = new Spko_Item;
                $spko_item['ordinal'] = $id_spko;
                $spko_item['id_product'] = $id_product;
                $spko_item['qty'] = $qty;
                $spko_item->save();
            }
            
        }
        
        return redirect('/');
    }

    public function editSpko(Request $request, $id_spko)
    {
        // Get Spko
        $spko = Spko::where('id_spko', $id_spko)->update(['employee'=>$request['employee'], 'trans_date' => $request['tanggal_transaksi'] ]);
        
        $return_url = '/spko/' . $id_spko;

        return redirect($return_url);
    }

    public function deleteSpko($id_spko)
    {
        // Delete spko_items first
        $spko_item = Spko_Item::where('ordinal', $id_spko)->delete();

        // Then delete the spko
        $spko = Spko::where('id_spko',$id_spko)->delete();
        return redirect('/');
    }


}
