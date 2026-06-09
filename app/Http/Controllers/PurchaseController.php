<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    private const SERVICE_FEE = 1000;

    private function formatTransactionDate($date): string
    {
        $date = $date instanceof Carbon
            ? $date
            : Carbon::parse($date, config('app.timezone'));

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $date = $date->copy()->timezone(config('app.timezone'));

        return $date->format('d') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y, H.i') . ' WIB';
    }

    public function create(Ebook $ebook)
    {
        if ($ebook->status !== 'published') {
            abort(404);
        }

        if ($ebook->isPurchasedBy(auth()->user())) {
            return redirect()->route('library')->with('info', 'You already own this ebook.');
        }

        $existingPurchase = null;

        return view('purchases.create', compact('ebook', 'existingPurchase'));
    }

    public function store(Request $request, Ebook $ebook)
    {
        if ($ebook->status !== 'published') {
            abort(404);
        }

        if ($ebook->isPurchasedBy(auth()->user())) {
            return redirect()->route('library')->with('info', 'You already own this ebook.');
        }

        $existingPurchase = Purchase::where('user_id', auth()->id())
            ->where('ebook_id', $ebook->id)
            ->where('payment_status', 'approved')
            ->first();

        if ($existingPurchase) {
            return redirect()->route('library')->with('info', 'You already own this ebook.');
        }

        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'notes'         => ['nullable', 'string', 'max:500'],
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        Purchase::create([
            'user_id'        => auth()->id(),
            'ebook_id'       => $ebook->id,
            'payment_proof'  => $path,
            'payment_status' => 'approved',
            'amount'         => $ebook->price,
            'notes'          => $request->notes,
        ]);

        return redirect()->route('library')->with('success', 'Pembayaran berhasil. E-book sudah tersedia di perpustakaan Anda.');
    }

    public function quickStore(Request $request, Ebook $ebook)
    {
        if ($ebook->status !== 'published') {
            abort(404);
        }

        $request->validate([
            'payment_method' => ['nullable', 'string', 'max:100'],
        ]);

        $existingPurchase = Purchase::where('user_id', auth()->id())
            ->where('ebook_id', $ebook->id)
            ->where('payment_status', 'approved')
            ->first();

        if ($existingPurchase) {
            return response()->json([
                'message' => 'You already own this ebook.',
                'transaction_id' => $this->displayTransactionId($existingPurchase),
                'status' => $existingPurchase->payment_status,
                'created_at' => $existingPurchase->created_at->toIso8601String(),
                'formatted_created_at' => $this->formatTransactionDate($existingPurchase->created_at),
                'paid_at' => $existingPurchase->paid_at?->toIso8601String(),
                'formatted_paid_at' => $this->formatNullableTransactionDate($existingPurchase->paid_at),
                'invoice_url' => route('purchase.invoice', $existingPurchase),
            ]);
        }

        $paymentMethod = $request->input('payment_method', 'SPA payment');

        $purchase = Purchase::create([
            'user_id' => auth()->id(),
            'ebook_id' => $ebook->id,
            'payment_status' => 'approved',
            'amount' => $ebook->price,
            'notes' => "Submitted from app payment page via {$paymentMethod}.",
        ]);

        return response()->json([
            'message' => 'Pembayaran berhasil. E-book sudah tersedia.',
            'transaction_id' => $this->displayTransactionId($purchase),
            'status' => $purchase->payment_status,
            'created_at' => $purchase->created_at->toIso8601String(),
            'formatted_created_at' => $this->formatTransactionDate($purchase->created_at),
            'paid_at' => $purchase->paid_at?->toIso8601String(),
            'formatted_paid_at' => $this->formatNullableTransactionDate($purchase->paid_at),
            'invoice_url' => route('purchase.invoice', $purchase),
        ], 201);
    }

    public function createMidtransTransaction(Request $request, Ebook $ebook)
    {
        if ($ebook->status !== 'published') {
            abort(404);
        }

        if ($ebook->isPurchasedBy(auth()->user())) {
            return response()->json([
                'message' => 'You already own this ebook.',
            ], 409);
        }

        $serverKey = config('services.midtrans.server_key');
        if (!$serverKey) {
            return response()->json([
                'message' => 'Midtrans server key belum dikonfigurasi.',
            ], 422);
        }

        $amount = $this->grossAmount($ebook);
        $orderId = 'EBOOK-' . now()->format('YmdHis') . '-' . auth()->id() . '-' . Str::upper(Str::random(6));

        $purchase = Purchase::create([
            'user_id' => auth()->id(),
            'ebook_id' => $ebook->id,
            'payment_status' => $amount <= 0 ? 'approved' : 'pending',
            'amount' => $amount,
            'notes' => 'Midtrans Sandbox transaction created from app payment page.',
            'midtrans_order_id' => $orderId,
            'midtrans_transaction_status' => $amount <= 0 ? 'settlement' : 'pending',
            'paid_at' => $amount <= 0 ? now() : null,
        ]);

        if ($amount <= 0) {
            return response()->json($this->purchaseResponse($purchase, 'E-book gratis berhasil ditambahkan.'));
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'custom_expiry' => [
                'order_time' => now()->format('Y-m-d H:i:s O'),
                'expiry_duration' => 1,
                'unit' => 'day',
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            'item_details' => [
                [
                    'id' => (string) $ebook->id,
                    'price' => (int) round($ebook->price),
                    'quantity' => 1,
                    'name' => Str::limit($ebook->title, 45, ''),
                    'category' => $ebook->category,
                ],
                [
                    'id' => 'SERVICE-FEE',
                    'price' => self::SERVICE_FEE,
                    'quantity' => 1,
                    'name' => 'Biaya Layanan',
                ],
            ],
            'enabled_payments' => [
                'credit_card',
                'gopay',
                'shopeepay',
                'qris',
                'bca_va',
                'bni_va',
                'bri_va',
                'permata_va',
                'echannel',
                'other_va',
                'Indomaret',
                'alfamart',
                'akulaku',
            ],
            'credit_card' => [
                'secure' => true,
            ],
            'callbacks' => [
                'finish' => route('midtrans.finish'),
                'unfinish' => route('midtrans.finish', ['result' => 'unfinished']),
                'error' => route('midtrans.finish', ['result' => 'error']),
            ],
        ];

        $midtransResponse = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->post(config('services.midtrans.snap_url'), $payload);

        if (!$midtransResponse->successful()) {
            $purchase->update([
                'payment_status' => 'rejected',
                'midtrans_transaction_status' => 'create_token_failed',
                'midtrans_response' => $midtransResponse->json() ?: ['body' => $midtransResponse->body()],
            ]);

            return response()->json([
                'message' => 'Gagal membuat transaksi Midtrans Sandbox.',
                'detail' => $midtransResponse->json(),
            ], 502);
        }

        $result = $midtransResponse->json();
        $purchase->update([
            'midtrans_snap_token' => $result['token'] ?? null,
            'midtrans_response' => $result,
        ]);

        return response()->json([
            'message' => 'Transaksi Midtrans Sandbox siap.',
            'snap_token' => $result['token'] ?? null,
            'redirect_url' => $result['redirect_url'] ?? null,
            'order_id' => $orderId,
            'transaction_id' => $this->displayTransactionId($purchase),
            'status' => $purchase->payment_status,
            'created_at' => $purchase->created_at->toIso8601String(),
            'formatted_created_at' => $this->formatTransactionDate($purchase->created_at),
            'paid_at' => $purchase->paid_at?->toIso8601String(),
            'formatted_paid_at' => $this->formatNullableTransactionDate($purchase->paid_at),
            'payment_method' => 'Midtrans Sandbox',
        ], 201);
    }

    public function completeMidtransTransaction(Request $request, Ebook $ebook)
    {
        $data = $request->validate([
            'order_id' => ['required', 'string', 'max:100'],
            'result' => ['nullable', 'array'],
        ]);

        $purchase = Purchase::where('user_id', auth()->id())
            ->where('ebook_id', $ebook->id)
            ->where('midtrans_order_id', $data['order_id'])
            ->firstOrFail();

        $result = $data['result'] ?? [];
        $this->applyMidtransStatus($purchase, $result, true);

        return response()->json($this->purchaseResponse($purchase->fresh(), 'Status pembayaran Midtrans berhasil disimpan.'));
    }

    public function midtransNotification(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;

        if (!$orderId || !$this->isValidMidtransSignature($payload)) {
            return response()->json(['message' => 'Invalid Midtrans notification.'], 403);
        }

        $purchase = Purchase::where('midtrans_order_id', $orderId)->firstOrFail();
        $this->applyMidtransStatus($purchase, $payload);

        return response()->json(['message' => 'Notification processed.']);
    }

    public function midtransFinish(Request $request)
    {
        $orderId = $request->query('order_id');
        $purchase = $orderId
            ? Purchase::with('ebook')->where('midtrans_order_id', $orderId)->first()
            : null;

        if ($purchase) {
            $this->refreshMidtransStatus($purchase);
            $purchase->refresh();
        }

        return redirect()->route('home', array_filter([
            'midtrans_result' => $request->query('result', 'finished'),
            'order_id' => $orderId,
            'ebook' => $purchase?->ebook?->slug,
            'status' => $purchase?->payment_status,
        ]));
    }

    public function status(Ebook $ebook)
    {
        $purchase = Purchase::where('user_id', auth()->id())
            ->where('ebook_id', $ebook->id)
            ->latest()
            ->firstOrFail();

        if ($purchase->midtrans_order_id && $purchase->payment_status === 'pending') {
            $this->refreshMidtransStatus($purchase);
            $purchase->refresh();
        }

        $qrCodeUrl = $this->midtransQrCodeUrl($purchase->midtrans_response ?? []);
        $midtransTransactionTime = $this->midtransTimestamp($purchase->midtrans_response ?? [], ['transaction_time']);
        $paidAt = $this->displayPaidAt($purchase);

        return response()->json([
            'transaction_id' => $this->displayTransactionId($purchase),
            'status' => $purchase->payment_status,
            'created_at' => $purchase->created_at->toIso8601String(),
            'formatted_created_at' => $this->formatTransactionDate($purchase->created_at),
            'paid_at' => $paidAt?->toIso8601String(),
            'formatted_paid_at' => $this->formatNullableTransactionDate($paidAt),
            'midtrans_transaction_time' => $midtransTransactionTime?->toIso8601String(),
            'formatted_midtrans_transaction_time' => $this->formatNullableTransactionDate($midtransTransactionTime),
            'payment_method' => $purchase->midtrans_payment_type
                ? 'Midtrans - ' . Str::headline($purchase->midtrans_payment_type)
                : ($purchase->notes && str_contains($purchase->notes, 'via ')
                ? rtrim(str($purchase->notes)->after('via ')->toString(), '.')
                : null),
            'order_id' => $purchase->midtrans_order_id,
            'midtrans_transaction_id' => $purchase->midtrans_transaction_id,
            'qr_code_url' => $qrCodeUrl,
            'qr_string' => $purchase->midtrans_response['qr_string'] ?? null,
            'invoice_url' => route('purchase.invoice', $purchase),
        ]);
    }

    public function invoice(Purchase $purchase)
    {
        // Ensure user can only see their own invoices
        if ($purchase->user_id !== auth()->id()) {
            abort(403);
        }

        $purchase->load(['user', 'ebook']);
        $transactionId = $this->displayTransactionId($purchase);
        $paidAt = $this->displayPaidAt($purchase);
        
        $paymentMethod = $purchase->midtrans_payment_type
            ? Str::headline($purchase->midtrans_payment_type)
            : ($purchase->notes && str_contains($purchase->notes, 'via ')
                ? rtrim(str($purchase->notes)->after('via ')->toString(), '.')
                : 'Manual Transfer');

        return view('purchases.invoice', compact('purchase', 'transactionId', 'paidAt', 'paymentMethod'));
    }

    private function grossAmount(Ebook $ebook): int
    {
        $subtotal = (int) round((float) $ebook->price);

        return $subtotal > 0 ? $subtotal + self::SERVICE_FEE : 0;
    }

    private function transactionCode(Purchase $purchase): string
    {
        return 'TRX-' . $purchase->created_at->format('Ymd') . '-' . str_pad((string) $purchase->id, 3, '0', STR_PAD_LEFT);
    }

    private function formatNullableTransactionDate($date): ?string
    {
        return $date ? $this->formatTransactionDate($date) : null;
    }

    private function displayTransactionId(Purchase $purchase): string
    {
        return $purchase->midtrans_transaction_id
            ?: $purchase->midtrans_order_id
            ?: $this->transactionCode($purchase);
    }

    private function midtransTimestamp(array $payload, array $keys): ?Carbon
    {
        foreach ($keys as $key) {
            if (empty($payload[$key])) {
                continue;
            }

            try {
                return Carbon::parse($payload[$key], config('app.timezone'));
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
    }

    private function displayPaidAt(Purchase $purchase): ?Carbon
    {
        return $this->midtransTimestamp($purchase->midtrans_response ?? [], ['settlement_time', 'transaction_time'])
            ?: $purchase->paid_at;
    }

    private function midtransQrCodeUrl(array $payload): ?string
    {
        $actions = collect($payload['actions'] ?? []);
        $qrAction = $actions->firstWhere('name', 'generate-qr-code-v2')
            ?: $actions->firstWhere('name', 'generate-qr-code');

        return is_array($qrAction) ? ($qrAction['url'] ?? null) : null;
    }

    private function purchaseResponse(Purchase $purchase, string $message): array
    {
        $midtransTransactionTime = $this->midtransTimestamp($purchase->midtrans_response ?? [], ['transaction_time']);
        $paidAt = $this->displayPaidAt($purchase);

        return [
            'message' => $message,
            'transaction_id' => $this->displayTransactionId($purchase),
            'order_id' => $purchase->midtrans_order_id,
            'status' => $purchase->payment_status,
            'created_at' => $purchase->created_at->toIso8601String(),
            'formatted_created_at' => $this->formatTransactionDate($purchase->created_at),
            'paid_at' => $paidAt?->toIso8601String(),
            'formatted_paid_at' => $this->formatNullableTransactionDate($paidAt),
            'midtrans_transaction_time' => $midtransTransactionTime?->toIso8601String(),
            'formatted_midtrans_transaction_time' => $this->formatNullableTransactionDate($midtransTransactionTime),
            'payment_method' => $purchase->midtrans_payment_type
                ? 'Midtrans - ' . Str::headline($purchase->midtrans_payment_type)
                : 'Midtrans Sandbox',
            'midtrans_transaction_id' => $purchase->midtrans_transaction_id,
            'qr_code_url' => $this->midtransQrCodeUrl($purchase->midtrans_response ?? []),
            'invoice_url' => route('purchase.invoice', $purchase),
        ];
    }

    private function applyMidtransStatus(Purchase $purchase, array $payload, bool $fromSnapCallback = false): void
    {
        $transactionStatus = $payload['transaction_status'] ?? ($fromSnapCallback ? 'settlement' : 'pending');
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentStatus = match ($transactionStatus) {
            'capture' => $fraudStatus === 'challenge' ? 'pending' : 'approved',
            'settlement', 'paid' => 'approved',
            'pending' => 'pending',
            'deny', 'cancel', 'expire', 'failure', 'rejected' => 'rejected',
            default => $purchase->payment_status ?: 'pending',
        };

        $purchase->update([
            'payment_status' => $paymentStatus,
            'midtrans_transaction_id' => $payload['transaction_id'] ?? $purchase->midtrans_transaction_id,
            'midtrans_payment_type' => $payload['payment_type'] ?? $purchase->midtrans_payment_type,
            'midtrans_transaction_status' => $transactionStatus,
            'midtrans_fraud_status' => $fraudStatus,
            'midtrans_response' => array_merge($purchase->midtrans_response ?? [], $payload),
            'paid_at' => $paymentStatus === 'approved'
                ? ($this->midtransTimestamp($payload, ['settlement_time', 'transaction_time']) ?: $purchase->paid_at ?: now())
                : $purchase->paid_at,
            'notes' => 'Midtrans Sandbox order ' . ($purchase->midtrans_order_id ?: '-') . ' status: ' . $transactionStatus . '.',
        ]);

        $purchase->transactionActivity?->update([
            'description' => 'Midtrans Sandbox order ' . ($purchase->midtrans_order_id ?: '-') . ' status: ' . $transactionStatus . '.',
            'amount' => $purchase->amount,
        ]);
    }

    private function isValidMidtransSignature(array $payload): bool
    {
        $serverKey = config('services.midtrans.server_key');
        $expected = hash('sha512', ($payload['order_id'] ?? '') . ($payload['status_code'] ?? '') . ($payload['gross_amount'] ?? '') . $serverKey);

        return !empty($payload['signature_key']) && hash_equals($expected, $payload['signature_key']);
    }

    private function refreshMidtransStatus(Purchase $purchase): void
    {
        $serverKey = config('services.midtrans.server_key');

        if (!$serverKey || !$purchase->midtrans_order_id) {
            return;
        }

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->get(config('services.midtrans.api_url') . '/' . rawurlencode($purchase->midtrans_order_id) . '/status');

        if ($response->successful()) {
            $this->applyMidtransStatus($purchase, $response->json() ?? []);
        }
    }
}
