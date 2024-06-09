<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Province;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use Illuminate\Support\Str;
use App\Models\Citie;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDF;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer.district.citie.province', 'payment'])
            ->orderBy('created_at', 'DESC');
        
        $bukti = Order::with('payment')->get();           

        if (request()->q != '') {
            $orders = $orders->where(function($q) {
                $q->where('customer_name', 'LIKE', '%' . request()->q . '%')
                ->orWhere('invoice', 'LIKE', '%' . request()->q . '%')
                ->orWhere('customer_address', 'LIKE', '%' . request()->q . '%');
            });
        }

        if (request()->status != '') {
            $orders = $orders->where('status', request()->status);
        }
        $orders = $orders->paginate(10);
        return view('orders.index', compact('orders', 'bukti'));
    }

    public function update(Request $request)
    {
        $orderId = $request->id;
    
        $orders = Order::where('id', $orderId)->update([
            'status' => $request->status,
        ]);
    
        $statusLabel = $this->getStatusLabel($request->status);
    
        session()->flash('success', "Status pesanan berhasil diubah menjadi $statusLabel");
        return redirect(route('order.index'));
    }
    
    // Method untuk mendapatkan label status
    private function getStatusLabel($status)
    {
        switch ($status) {
            case 0:
                return 'Baru';
            case 1:
                return 'Dikonfirmasi';
            case 2:
                return 'Proses';
            case 3:
                return 'Dikirim';
            case 4:
                return 'Tunggu customer update ke Selesai';
            default:
                return 'Unknown';
        }
    }

    public function destroy($id){
        $order = Order::find($id);
        $order->details()->delete();
        $order->payment()->delete();
        $order->delete();
        return redirect(route('order.index'));
    }

    public function viewOrder()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        if (request()->date != '') {
            $date = explode(' - ' ,request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }

        $orders = Order::with(['customer.district'])->whereBetween('created_at', [$start, $end])->get();
        return view('orders.view', compact('orders'));
    }

    public function orderReportPdf($daterange)
    {
        $date = explode('+', $daterange);
        $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';

        $orders = Order::with(['customer.district'])->whereBetween('created_at', [$start, $end])->get();
        $pdf = PDF::loadView('orders.order_pdf', compact('orders', 'date'));
        return $pdf->stream();
    }
}
