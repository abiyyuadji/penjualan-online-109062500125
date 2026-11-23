<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->cartData($request);

        // Buang produk nonaktif/terhapus dari session agar jumlah di header tetap benar.
        $request->session()->put('cart', $data['items']->mapWithKeys(
            fn ($item) => [$item['product']->id => $item['quantity']]
        )->all());

        return view('cart.index', $data);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);
        $newQty = ($cart[$product->id] ?? 0) + (int) $data['quantity'];

        if ($newQty > $product->stock) {
            return back()->withErrors(['quantity' => 'Jumlah melebihi stok yang tersedia.']);
        }

        $cart[$product->id] = $newQty;
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (! isset($cart[$product->id])) {
            return back()->with('error', 'Produk tidak ada di keranjang.');
        }

        if ((int) $data['quantity'] > $product->stock) {
            return back()->withErrors(['quantity' => "Stok {$product->name} hanya {$product->stock}."]);
        }

        $cart[$product->id] = (int) $data['quantity'];
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    private function cartData(Request $request): array
    {
        $cart = $request->session()->get('cart', []);

        $products = Product::whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $items = collect();
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            $lineTotal = (float) $product->price * $quantity;
            $subtotal += $lineTotal;

            $items->push(compact('product', 'quantity', 'lineTotal'));
        }

        return compact('items', 'subtotal');
    }
}