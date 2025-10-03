<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        return view('admin.transaction.index', compact('transactions'));
    }
    public function payment(Request  $request)
    {
        // dd($request->all());
        $transaction = Transaction::create([
            'payment_status' => 0,
            'total_price' => $request->total_price,
            'cash' => $request->cash - $request->kembalian,
            'debit' => $request->debit,
            'kembalian' => $request->kembalian,
            'note' => "Produk Terjual",
        ]);
        if ($request->order_type == 1) {
            $order = Order::find($request->order_id)->update([
                'status' => 3,
                'transaction_id' => $transaction->id,
            ]);
            return redirect()->to('admin/order/manual/' . $request->order_id);
        } else {
            $orders = Order::where('customer_id', $request->customer_id)->where('status', 2)->update(['status' => 3, 'transaction_id' => $transaction->id]);
            return redirect()->to('admin/order/online/' . $request->customer_id);
        }
    }
    public function invoiceOnline($id)
    {
        $transaction = Transaction::find($id);
        $orders = Order::with('products')->where('transaction_id', $id)->get();
        return view('admin.transaction.invoiceOnline', compact('transaction', 'orders'));
    }
    public function invoiceManual($id)
    {
        $transaction = Transaction::find($id);
        $order = Order::with('products')->where('transaction_id', $id)->first();
        return view('admin.transaction.invoiceManual', compact('transaction', 'order'));
    }
    public function summary($id)
    {

        if ($id == 1) {
            $transactions = Transaction::orderBy('created_at',  'desc')->get()->groupBy(function ($item) {
                return $item->created_at->format('d-m-Y');
            });
        } else {
            $transactions = Transaction::orderBy('created_at',  'desc')->get()->groupBy(function ($item) {
                return $item->created_at->format('m-Y');
            });
        }

        return view('admin.transaction.summary', compact('transactions', 'id'));
    }
    public function summaryShow($date)
    {
        if (strlen($date) > 7) {
            $transactions = Transaction::where(DB::raw("(DATE_FORMAT(created_at,'%d-%m-%Y'))"), '=', $date)->orderBy('created_at',  'asc')->get();
        } else {
            $transactions = Transaction::where(DB::raw("(DATE_FORMAT(created_at,'%m-%Y'))"), '=', $date)->orderBy('created_at',  'asc')->get();
        }

        return view('admin.transaction.summaryShow', compact('transactions', 'date'));
    }

    public function create()
    {
        return view('admin.transaction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'payment_status' => 'required',
        ]);

        Transaction::create([
            'payment_status' => $request->payment_status,
            'cash' => $request->cash,
            'debit' => $request->debit,
            'total_price' => $request->cash + $request->debit,
            'note' => $request->note,
        ]);

        return redirect()->to('admin/transaction');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('admin.transaction.show', compact('transaction'));
    }

    public function sale($id)
    {
        if ($id == 0) {
            $products = Product::whereHas('orders')->get()->groupBy(function ($item) {
                return $item->created_at->format('d-m-Y');
            });;
        } else {
            $products = Product::whereHas('orders')->get()->groupBy(function ($item) {
                return $item->created_at->format('m-Y');
            });;
        }

        // dd($products);
        return view('admin.transaction.sale', compact('products'));
    }

    public function saleShow($date)
    {
        if (strlen($date) > 7) {
            $products = Product::whereHas('orders')->where(DB::raw("(DATE_FORMAT(created_at,'%d-%m-%Y'))"), '=', $date)->orderBy('created_at',  'desc')->get();
        } else {
            $products = Product::whereHas('orders')->where(DB::raw("(DATE_FORMAT(created_at,'%m-%Y'))"), '=', $date)->orderBy('created_at',  'desc')->get();
        }
        return view('admin.transaction.saleShow', compact('products', 'date'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function operatorDestroy($id)
    {
        Transaction::destroy($id);
        return redirect()->to('/admin/transaction')->with('danger', 'transaksi dihapus:(');
    }
}
