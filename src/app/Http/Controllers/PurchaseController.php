<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    private PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $address = session('purchase_address.' . $item_id, [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('purchases.create', compact('item', 'user', 'address'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $data = $request->validated();

        if ($item->purchase) {
            return redirect('/')
                ->with('error', 'この商品はすでに購入されています。');
        }

        $checkoutSession = $this->purchaseService->createCheckoutSession($item, $user, $data);

        if (! $checkoutSession) {
            return back()
                ->withErrors(['payment_method' => '支払い方法を選択してください'])
                ->withInput();
        }

        session()->forget('purchase_address.' . $item_id);

        return redirect()->away($checkoutSession->url);
    }

    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);

        if (! $item->purchase) {
            $sessionId = request('session_id');

            if (! $sessionId) {
                return redirect('/')
                    ->with('error', '決済情報を確認できませんでした。');
            }

            $checkoutSession = $this->purchaseService->retrieveCheckoutSession($sessionId);

            if ($checkoutSession->payment_status !== 'paid') {
                return redirect('/')
                    ->with('error', '決済が完了していません。');
            }

            Purchase::create([
                'user_id' => $checkoutSession->metadata->user_id,
                'item_id' => $checkoutSession->metadata->item_id,
                'payment_method' => $checkoutSession->metadata->payment_method,
                'postal_code' => $checkoutSession->metadata->postal_code,
                'address' => $checkoutSession->metadata->address,
                'building' => $checkoutSession->metadata->building ?: null,
            ]);
        }

        return redirect('/')
            ->with('success', '購入が完了しました。');
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $address = session('purchase_address.' . $item_id, [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('purchases.address', compact('item', 'user', 'address'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $data = $request->validated();

        $address = [
            'postal_code' => $data['postal_code'],
            'address' => $data['address'],
            'building' => $data['building'] ?? null,
        ];

        session()->put('purchase_address.' . $item_id, $address);

        return redirect('/purchase/' . $item_id);
    }
}