<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Order extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'phone',
        'address',
        'status',
        'image',
        'total',
        'profit',
    ];

    protected function image(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => $value
        );
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    protected static function booted(): void
    {
        // When creating a new order
        static::creating(function (Order $order) {
            $qty = max(1, (int) ($order->quantity ?? 1));
            if ($order->status === 'approved') {
                static::decrementProductStockOrFail($order->product_id, $qty);
            }
        });

        // When updating an existing order
        static::updating(function (Order $order) {
            $originalStatus = $order->getOriginal('status');
            $originalProductId = $order->getOriginal('product_id');
            $originalQty = (int) ($order->getOriginal('quantity') ?? 1);
            $newQty = max(1, (int) ($order->quantity ?? 1));

            // If status changed
            if ($order->isDirty('status')) {
                // Transition to approved: decrement stock by newQty
                if ($originalStatus !== 'approved' && $order->status === 'approved') {
                    static::decrementProductStockOrFail($order->product_id, $newQty);
                }

                // Transition away from approved: restore stock by originalQty
                if ($originalStatus === 'approved' && $order->status !== 'approved') {
                    static::incrementProductStock($originalProductId, $originalQty);
                }
            }

            // If product changed while already approved, move the stock
            if ($order->isDirty('product_id') && $order->status === 'approved') {
                if ($originalProductId && $originalProductId !== $order->product_id) {
                    static::incrementProductStock($originalProductId, $originalQty);
                    static::decrementProductStockOrFail($order->product_id, $newQty);
                }
            }

            // If quantity changed while approved, adjust the difference on the same product
            if ($order->isDirty('quantity') && $order->status === 'approved' && $order->product_id === $originalProductId) {
                $diff = $newQty - $originalQty;
                if ($diff > 0) {
                    static::decrementProductStockOrFail($order->product_id, $diff);
                } elseif ($diff < 0) {
                    static::incrementProductStock($order->product_id, -$diff);
                }
            }
        });

        // When soft-deleting an order, return stock if it was approved
        static::deleting(function (Order $order) {
            if ($order->status === 'approved') {
                $qty = max(1, (int) ($order->quantity ?? 1));
                static::incrementProductStock($order->product_id, $qty);
            }
        });

        // When restoring a soft-deleted order, reduce stock again
        static::restoring(function (Order $order) {
            if ($order->status === 'approved') {
                $qty = max(1, (int) ($order->quantity ?? 1));
                static::decrementProductStockOrFail($order->product_id, $qty);
            }
        });
    }

    /**
     * Decrement product stock with row-level lock. Throws ValidationException if not enough stock.
     */
    protected static function decrementProductStockOrFail(int $productId, int $qty): void
    {
        DB::transaction(function () use ($productId, $qty) {
            $product = Product::whereKey($productId)->lockForUpdate()->first();
            if (! $product) {
                throw ValidationException::withMessages(['product_id' => 'Produk tidak ditemukan.']);
            }
            if ($product->stock < $qty) {
                throw ValidationException::withMessages(['stock' => 'Stok produk tidak mencukupi.']);
            }
            $product->decrement('stock', $qty);
        }, 3);
    }

    /**
     * Increment product stock with row-level lock (best-effort).
     */
    protected static function incrementProductStock(int $productId, int $qty): void
    {
        DB::transaction(function () use ($productId, $qty) {
            $product = Product::whereKey($productId)->lockForUpdate()->first();
            if ($product) {
                $product->increment('stock', $qty);
            }
        }, 3);
    }
}
