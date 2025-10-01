<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $dataOrder = Order::select('id', 'customer_name', 'table_no', 'order_date', 'order_time', 'status', 'total')->get();

        return response(['data' => $dataOrder]);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return $order->loadMissing('orderDetail:order_id,price,item_id', 'orderDetail.item:id,name', 'waiterss:id,name', 'cashier:id,name');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|max:100',
            'table_no' => 'required|max:5',
        ]);

        try {
            DB::beginTransaction();
            $data = $request->only('customer_name', 'table_no');
            $data['order_date'] = date('Y-m-d');
            $data['order_time'] = date('H:i:s');
            $data['status'] = 'ordered';
            $data['total'] = 0;
            $data['waiterss_id'] = $request->user()->id;
            $data['items'] = $request->items;

            $order = Order::create($data);

            collect($data['items'])->map(function ($item) use ($order) {
                $foodDrink = Item::where('id', $item)->first();
                OrderDetail::create([
                    'order_id' => $order->id,
                    'item_id' => $item,
                    'price' => $foodDrink->price
                ]);
            });

            $order->total = $order->sumOrderPrice();
            $order->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th);
        }

        return response(['data' => $order]);
    }

    public function setAsDone($id)
    {
      $order = Order::findOrFail($id);

      if ($order->status != 'ordered') {
        return response('order cannot set to done because the status is not ordered', 403);
      }

      $order->status = 'done';

      $order->save();
      return response(['data' => $order]);
    }
}
