<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\XlsxExportable;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    use XlsxExportable;

    public function index()
    {
        $purchases = Purchase::with(['user', 'ebook'])->latest()->paginate(15);
        return view('admin.payments.index', compact('purchases'));
    }

    public function invoice(Purchase $purchase)
    {
        $purchase->load(['user', 'ebook']);
        
        // Use the same helper logic to get transaction display data
        $transactionId = $purchase->midtrans_transaction_id ?: $purchase->midtrans_order_id ?: ('TRX-' . $purchase->created_at->format('Ymd') . '-' . str_pad((string) $purchase->id, 3, '0', STR_PAD_LEFT));
        
        $paidAt = $purchase->paid_at;
        
        $paymentMethod = $purchase->midtrans_payment_type
            ? Str::headline($purchase->midtrans_payment_type)
            : ($purchase->notes && str_contains($purchase->notes, 'via ')
                ? rtrim(str($purchase->notes)->after('via ')->toString(), '.')
                : 'Manual Transfer');

        return view('purchases.invoice', compact('purchase', 'transactionId', 'paidAt', 'paymentMethod'));
    }

    public function report(Request $request)
    {
        $period = $request->get('period', '30days');
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->endOfDay();

        if ($period === '7days') {
            $startDate = now()->subDays(6)->startOfDay();
        } elseif ($period === 'this_month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } elseif ($period === 'last_month') {
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();
        } elseif ($period === 'custom' && $request->get('start_date') && $request->get('end_date')) {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
        }

        $applySearch = function ($query) use ($search) {
            if (! $search) {
                return $query;
            }

            return $query->where(function ($q) use ($search) {
                $q->where('purchases.midtrans_order_id', 'like', "%{$search}%")
                    ->orWhere('purchases.id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('ebook', function ($eq) use ($search) {
                        $eq->where('title', 'like', "%{$search}%");
                    });
            });
        };

        $approvedSalesQuery = $applySearch(
            Purchase::where('payment_status', 'approved')
                ->whereBetween('purchases.created_at', [$startDate, $endDate])
        );

        $totalRevenue = (clone $approvedSalesQuery)->sum('amount');
        $totalSales = (clone $approvedSalesQuery)->count();
            
        $pendingSales = Purchase::where('payment_status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $salesSummaryTitle = 'Sales Recap';
        $salesSummaryPeriodLabel = 'Date';

        $salesSummary = collect([
            (object) [
                'label' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
                'count' => $totalSales,
                'revenue' => $totalRevenue,
            ],
        ]);

        $topEbooks = (clone $approvedSalesQuery)
            ->with('ebook')
            ->selectRaw('ebook_id, COUNT(*) as count, SUM(amount) as revenue')
            ->groupBy('ebook_id')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        $recentSales = Purchase::with(['user', 'ebook'])
            ->where('payment_status', 'approved')
            ->latest()
            ->limit(10)
            ->get();

        $pendingTransactions = Purchase::with(['user', 'ebook'])
            ->where('payment_status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        $filteredTransactions = (clone $approvedSalesQuery)->with(['user', 'ebook'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        // Chart Data: Daily Revenue for selected range
        $dailySalesRaw = (clone $approvedSalesQuery)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('revenue', 'date');

        $chartLabels = [];
        $chartData = [];
        
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $chartLabels[] = $current->format('d M');
            $chartData[] = (float) ($dailySalesRaw[$dateStr] ?? 0);
            $current->addDay();
        }

        // Category Distribution for selected range
        $categoryData = (clone $approvedSalesQuery)
            ->join('ebooks', 'purchases.ebook_id', '=', 'ebooks.id')
            ->selectRaw('ebooks.category, COUNT(*) as count')
            ->groupBy('ebooks.category')
            ->get();

        return view('admin.payments.report', compact(
            'totalRevenue',
            'totalSales',
            'pendingSales',
            'salesSummary',
            'salesSummaryTitle',
            'salesSummaryPeriodLabel',
            'topEbooks',
            'recentSales',
            'pendingTransactions',
            'filteredTransactions',
            'chartLabels',
            'chartData',
            'categoryData',
            'period',
            'startDate',
            'endDate'
        ));
    }

    public function exportReport(Request $request)
    {
        $period = $request->get('period', '30days');
        $search = $request->get('search');
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->endOfDay();

        if ($period === '7days') {
            $startDate = now()->subDays(6)->startOfDay();
        } elseif ($period === 'this_month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } elseif ($period === 'last_month') {
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();
        } elseif ($period === 'custom' && $request->get('start_date') && $request->get('end_date')) {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
        }

        $purchasesQuery = Purchase::with(['user', 'ebook'])
            ->where('payment_status', 'approved')
            ->whereBetween('purchases.created_at', [$startDate, $endDate]);

        if ($search) {
            $purchasesQuery->where(function ($q) use ($search) {
                $q->where('purchases.midtrans_order_id', 'like', "%{$search}%")
                    ->orWhere('purchases.id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('ebook', function ($eq) use ($search) {
                        $eq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $totalRevenue = (clone $purchasesQuery)->sum('amount');
        $totalSales = (clone $purchasesQuery)->count();
        $purchases = (clone $purchasesQuery)
            ->latest()
            ->get();

        $salesSummary = collect([
            (object) [
                'label' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
                'count' => $totalSales,
                'revenue' => $totalRevenue,
            ],
        ]);

        return view('admin.payments.export', compact(
            'purchases',
            'salesSummary',
            'totalRevenue',
            'totalSales',
            'startDate',
            'endDate',
            'period',
            'search'
        ));
    }

    public function exportReportExcel(Request $request)
    {
        $period = $request->get('period', '30days');
        $search = $request->get('search');
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->endOfDay();

        if ($period === '7days') {
            $startDate = now()->subDays(6)->startOfDay();
        } elseif ($period === 'this_month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } elseif ($period === 'last_month') {
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();
        } elseif ($period === 'custom' && $request->get('start_date') && $request->get('end_date')) {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
        }

        $purchasesQuery = Purchase::with(['user', 'ebook'])
            ->where('payment_status', 'approved')
            ->whereBetween('purchases.created_at', [$startDate, $endDate]);

        if ($search) {
            $purchasesQuery->where(function ($q) use ($search) {
                $q->where('purchases.midtrans_order_id', 'like', "%{$search}%")
                    ->orWhere('purchases.id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('ebook', function ($eq) use ($search) {
                        $eq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $totalRevenue = (clone $purchasesQuery)->sum('amount');
        $totalSales = (clone $purchasesQuery)->count();
        $purchases = (clone $purchasesQuery)
            ->latest()
            ->get();

        $filename = 'sales-report-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.xlsx';
        $viewData = compact(
            'purchases',
            'totalRevenue',
            'totalSales',
            'startDate',
            'endDate',
            'period',
            'search',
            'filename'
        );

        if ($request->boolean('download')) {
            return response($this->buildSalesReportXlsx($viewData))
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'max-age=0');
        }

        return view('admin.payments.export-excel-preview', $viewData);
    }

    private function buildSalesReportXlsx(array $data): string
    {
        $rows = [
            [
                ['value' => 'SALES TRANSACTION REPORT', 'style' => 1],
            ],
            [
                ['value' => 'EBOOK STORE SYSTEM', 'style' => 2],
            ],
            [
                ['value' => 'Period: ' . $data['startDate']->format('d F Y') . ' - ' . $data['endDate']->format('d F Y') . ($data['search'] ? ' | Search: ' . $data['search'] : ''), 'style' => 2],
            ],
            [],
            [
                ['value' => 'Total Successful Transactions', 'style' => 3],
                ['value' => '', 'style' => 3],
                ['value' => $data['totalSales'], 'type' => 'number', 'style' => 4],
                ['value' => 'Total Revenue', 'style' => 3],
                ['value' => '', 'style' => 3],
                ['value' => '', 'style' => 3],
                ['value' => (int) $data['totalRevenue'], 'type' => 'number', 'style' => 4],
                ['value' => '', 'style' => 4],
                ['value' => '', 'style' => 4],
            ],
            [],
            [
                ['value' => 'No', 'style' => 5],
                ['value' => 'Date', 'style' => 5],
                ['value' => 'Order ID', 'style' => 5],
                ['value' => 'Customer Name', 'style' => 5],
                ['value' => 'Customer Email', 'style' => 5],
                ['value' => 'Ebook', 'style' => 5],
                ['value' => 'Method', 'style' => 5],
                ['value' => 'Paid At', 'style' => 5],
                ['value' => 'Amount', 'style' => 5],
            ],
        ];

        foreach ($data['purchases'] as $index => $purchase) {
            $rows[] = [
                ['value' => $index + 1, 'type' => 'number'],
                ['value' => $purchase->created_at->format('d/m/Y H:i')],
                ['value' => $purchase->midtrans_order_id ?? 'TRX-' . $purchase->id],
                ['value' => $purchase->user->name ?? 'Guest'],
                ['value' => $purchase->user->email ?? '-'],
                ['value' => $purchase->ebook->title ?? '-'],
                ['value' => $purchase->midtrans_payment_type ? Str::headline($purchase->midtrans_payment_type) : 'Manual Transfer'],
                ['value' => $purchase->paid_at ? $purchase->paid_at->format('d/m/Y H:i') : '-'],
                ['value' => (int) $purchase->amount, 'type' => 'number'],
            ];
        }

        if ($data['purchases']->isEmpty()) {
            $rows[] = [
                ['value' => 'No transaction details found for this period.', 'style' => 2],
            ];
        }

        $emptyRow = $data['purchases']->isEmpty() ? count($rows) : null;
        $rows[] = [
            ['value' => 'TOTAL', 'style' => 3],
            ['value' => '', 'style' => 3],
            ['value' => '', 'style' => 3],
            ['value' => '', 'style' => 3],
            ['value' => '', 'style' => 3],
            ['value' => '', 'style' => 3],
            ['value' => '', 'style' => 3],
            ['value' => '', 'style' => 3],
            ['value' => (int) $data['totalRevenue'], 'type' => 'number', 'style' => 3],
        ];

        $merges = ['A1:I1', 'A2:I2', 'A3:I3', 'A5:B5', 'D5:F5'];

        if ($emptyRow) {
            $merges[] = 'A' . $emptyRow . ':I' . $emptyRow;
        }

        $worksheetOptions = [
            'columns' => [7, 18, 28, 28, 34, 42, 20, 20, 18],
            'merges' => $merges,
            'freezePane' => 'A8',
            'autoFilter' => 'A7:I' . max(7, count($rows) - 1),
            'rowHeights' => [1 => 24, 2 => 20, 3 => 20],
        ];

        return $this->createXlsxArchive([
            '[Content_Types].xml' => $this->xlsxContentTypes(),
            '_rels/.rels' => $this->xlsxRootRelationships(),
            'docProps/app.xml' => $this->xlsxAppProperties(),
            'docProps/core.xml' => $this->xlsxCoreProperties(),
            'xl/workbook.xml' => $this->xlsxWorkbook(),
            'xl/_rels/workbook.xml.rels' => $this->xlsxWorkbookRelationships(),
            'xl/styles.xml' => $this->xlsxStyles(),
            'xl/worksheets/sheet1.xml' => $this->xlsxWorksheet($rows, $worksheetOptions),
        ]);
    }
}
