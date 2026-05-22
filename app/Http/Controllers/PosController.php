<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PosController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $discount = $request->discount ?? 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk: {$product->name}");
                }

                $product->stock -= $item['quantity'];
                $product->save();

                $subtotal += $product->price * $item['quantity'];
                
                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_at_sale' => $product->price
                ];
            }

            $tax = $subtotal * 0.11; // 11% tax
            $total_pay = $subtotal + $tax - $discount;

            $transaction = Transaction::create([
                'user_id' => $request->user()->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total_pay' => $total_pay,
            ]);

            foreach ($itemsData as $itemData) {
                $itemData['transaction_id'] = $transaction->id;
                TransactionItem::create($itemData);
            }

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil',
                'transaction' => $transaction->load('items')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Transaksi gagal',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function history(Request $request)
    {
        $transactions = Transaction::with(['items.product'])
            ->whereDate('created_at', Carbon::today())
            ->get();

        return response()->json([
            'history' => $transactions
        ]);
    }

    public function receipt($id)
    {
        $transaction = Transaction::with(['items.product', 'user'])->find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json([
            'transaction' => $transaction
        ]);
    }
}
