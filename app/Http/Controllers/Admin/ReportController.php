<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\Purchase;
use App\Models\TransactionActivity;
use App\Models\User;
use App\Http\Controllers\Concerns\XlsxExportable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use XlsxExportable;

    public function users(Request $request)
    {
        [$period, $startDate, $endDate] = $this->resolvePeriod($request);

        $usersQuery = fn () => User::query()->where('role', 'user');
        $loginDateExpression = DB::raw('COALESCE(last_login_at, created_at)');

        $monthlyUsers = User::query()
            ->selectRaw('DATE_FORMAT(COALESCE(last_login_at, created_at), "%Y-%m") as month, COUNT(*) as total')
            ->where('role', 'user')
            ->whereBetween($loginDateExpression, [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $labels = $this->monthLabels($startDate, $endDate);
        $growthData = collect($labels)->map(fn ($label, $month) => (int) ($monthlyUsers[$month] ?? 0));

        return view('admin.reports.users', [
            'totalUsers' => $usersQuery()->where('created_at', '<=', $endDate)->count(),
            'newUsers' => $usersQuery()->whereBetween($loginDateExpression, [$startDate, $endDate])->count(),
            'registrationHistory' => $usersQuery()
                ->whereBetween($loginDateExpression, [$startDate, $endDate])
                ->orderByDesc($loginDateExpression)
                ->limit(10)
                ->get(),
            'chartLabels' => $labels->values(),
            'growthData' => $growthData->values(),
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function ebooks(Request $request)
    {
        [$period, $startDate, $endDate] = $this->resolvePeriod($request);

        $totalEbooks = Ebook::count();
        $totalFreeEbooks = Ebook::where('price', '<=', 0)->count();
        $totalPaidEbooks = Ebook::where('price', '>', 0)->count();

        $approvedPurchasesInPeriod = Purchase::query()
            ->where('payment_status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate]);

        $salesByEbook = $approvedPurchasesInPeriod
            ->selectRaw('ebook_id, COUNT(*) as sales_count, SUM(amount) as revenue')
            ->groupBy('ebook_id')
            ->orderByDesc('sales_count')
            ->limit(10)
            ->get()
            ->keyBy('ebook_id');

        $topSellingEbookIds = $salesByEbook->pluck('ebook_id');

        $topSellingEbooks = Ebook::query()
            ->whereIn('id', $topSellingEbookIds)
            ->get()
            ->keyBy('id')
            ->map(function (Ebook $ebook) use ($salesByEbook) {
                $stats = $salesByEbook->get($ebook->id);
                $ebook->sales_count = (int) ($stats->sales_count ?? 0);
                $ebook->revenue = (float) ($stats->revenue ?? 0);

                return $ebook;
            })
            ->filter(fn (Ebook $ebook) => $ebook->sales_count > 0)
            ->sortByDesc('sales_count')
            ->values();

        $categoryStats = Ebook::query()
            ->select('category', DB::raw('COUNT(*) as total'))
            ->whereIn('id', $topSellingEbookIds)
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('admin.reports.ebooks', [
            'totalEbooks' => $totalEbooks,
            'totalFreeEbooks' => $totalFreeEbooks,
            'totalPaidEbooks' => $totalPaidEbooks,
            'topSellingEbooks' => $topSellingEbooks,
            'categoryStats' => $categoryStats,
            'categoryLabels' => $categoryStats->pluck('category'),
            'categoryData' => $categoryStats->pluck('total'),
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function transactions(Request $request)
    {
        [$period, $startDate, $endDate] = $this->resolvePeriod($request);

        $monthlyTransactions = Purchase::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $labels = $this->monthLabels($startDate, $endDate);
        $transactionData = collect($labels)->map(fn ($label, $month) => (int) ($monthlyTransactions[$month] ?? 0));
        $activities = TransactionActivity::with('user', 'ebook', 'purchase')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.reports.transactions', [
            'totalTransactions' => Purchase::whereBetween('created_at', [$startDate, $endDate])->count(),
            'paidTransactions' => Purchase::where('payment_status', 'approved')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'pendingTransactions' => Purchase::where('payment_status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'activities' => $activities,
            'chartLabels' => $labels->values(),
            'transactionData' => $transactionData->values(),
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function exportUsers(Request $request)
    {
        [$period, $startDate, $endDate] = $this->resolvePeriod($request);

        $loginDateExpression = DB::raw('COALESCE(last_login_at, created_at)');
        $userRecords = User::query()
            ->where('role', 'user')
            ->whereBetween($loginDateExpression, [$startDate, $endDate])
            ->orderByDesc($loginDateExpression)
            ->get();

        return view('admin.reports.export-users', [
            'totalUsers' => User::where('role', 'user')->where('created_at', '<=', $endDate)->count(),
            'newUsers' => $userRecords->count(),
            'userRecords' => $userRecords,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function exportUsersExcel(Request $request)
    {
        [$period, $startDate, $endDate] = $this->resolvePeriod($request);

        $loginDateExpression = DB::raw('COALESCE(last_login_at, created_at)');
        $userRecords = User::query()
            ->where('role', 'user')
            ->whereBetween($loginDateExpression, [$startDate, $endDate])
            ->orderByDesc($loginDateExpression)
            ->get();

        $data = [
            'totalUsers' => User::where('role', 'user')->where('created_at', '<=', $endDate)->count(),
            'newUsers' => $userRecords->count(),
            'userRecords' => $userRecords,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        $filename = 'user-report-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.xlsx';

        if ($request->boolean('download')) {
            return response($this->buildUsersReportXlsx($data))
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'max-age=0');
        }

        return view('admin.reports.export-users-excel-preview', $data);
    }

    public function exportEbooks(Request $request)
    {
        [$period, $startDate, $endDate] = $this->resolvePeriod($request);

        $totalEbooks = Ebook::count();
        $totalFreeEbooks = Ebook::where('price', '<=', 0)->count();
        $totalPaidEbooks = Ebook::where('price', '>', 0)->count();

        $salesByEbook = Purchase::query()
            ->where('payment_status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('ebook_id, COUNT(*) as sales_count, SUM(amount) as revenue')
            ->groupBy('ebook_id')
            ->orderByDesc('sales_count')
            ->limit(10)
            ->get()
            ->keyBy('ebook_id');

        $topSellingEbookIds = $salesByEbook->pluck('ebook_id');

        $topSellingEbooks = Ebook::query()
            ->whereIn('id', $topSellingEbookIds)
            ->get()
            ->keyBy('id')
            ->map(function (Ebook $ebook) use ($salesByEbook) {
                $stats = $salesByEbook->get($ebook->id);
                $ebook->sales_count = (int) ($stats->sales_count ?? 0);
                $ebook->revenue = (float) ($stats->revenue ?? 0);

                return $ebook;
            })
            ->filter(fn (Ebook $ebook) => $ebook->sales_count > 0)
            ->sortByDesc('sales_count')
            ->values();

        $categoryStats = Ebook::query()
            ->select('category', DB::raw('COUNT(*) as total'))
            ->whereIn('id', $topSellingEbookIds)
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('admin.reports.export-ebooks', [
            'totalEbooks' => $totalEbooks,
            'totalFreeEbooks' => $totalFreeEbooks,
            'totalPaidEbooks' => $totalPaidEbooks,
            'topSellingEbooks' => $topSellingEbooks,
            'categoryStats' => $categoryStats,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function exportEbooksExcel(Request $request)
    {
        [$period, $startDate, $endDate] = $this->resolvePeriod($request);

        $totalEbooks = Ebook::count();
        $totalFreeEbooks = Ebook::where('price', '<=', 0)->count();
        $totalPaidEbooks = Ebook::where('price', '>', 0)->count();

        $salesByEbook = Purchase::query()
            ->where('payment_status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('ebook_id, COUNT(*) as sales_count, SUM(amount) as revenue')
            ->groupBy('ebook_id')
            ->orderByDesc('sales_count')
            ->limit(10)
            ->get()
            ->keyBy('ebook_id');

        $topSellingEbookIds = $salesByEbook->pluck('ebook_id');

        $topSellingEbooks = Ebook::query()
            ->whereIn('id', $topSellingEbookIds)
            ->get()
            ->keyBy('id')
            ->map(function (Ebook $ebook) use ($salesByEbook) {
                $stats = $salesByEbook->get($ebook->id);
                $ebook->sales_count = (int) ($stats->sales_count ?? 0);
                $ebook->revenue = (float) ($stats->revenue ?? 0);

                return $ebook;
            })
            ->filter(fn (Ebook $ebook) => $ebook->sales_count > 0)
            ->sortByDesc('sales_count')
            ->values();

        $categoryStats = Ebook::query()
            ->select('category', DB::raw('COUNT(*) as total'))
            ->whereIn('id', $topSellingEbookIds)
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $data = [
            'totalEbooks' => $totalEbooks,
            'totalFreeEbooks' => $totalFreeEbooks,
            'totalPaidEbooks' => $totalPaidEbooks,
            'topSellingEbooks' => $topSellingEbooks,
            'categoryStats' => $categoryStats,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        $filename = 'ebook-report-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.xlsx';

        if ($request->boolean('download')) {
            return response($this->buildEbookReportXlsx($data))
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'max-age=0');
        }

        return view('admin.reports.export-ebooks-excel-preview', $data);
    }

    public function exportTransactions(Request $request)
    {
        [$period, $startDate, $endDate] = $this->resolvePeriod($request);

        $activities = TransactionActivity::with('user', 'ebook', 'purchase')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        return view('admin.reports.export-transactions', [
            'totalTransactions' => Purchase::whereBetween('created_at', [$startDate, $endDate])->count(),
            'paidTransactions' => Purchase::where('payment_status', 'approved')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'pendingTransactions' => Purchase::where('payment_status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'activities' => $activities,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function exportTransactionsExcel(Request $request)
    {
        [$period, $startDate, $endDate] = $this->resolvePeriod($request);

        $activities = TransactionActivity::with('user', 'ebook', 'purchase')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $data = [
            'totalTransactions' => Purchase::whereBetween('created_at', [$startDate, $endDate])->count(),
            'paidTransactions' => Purchase::where('payment_status', 'approved')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'pendingTransactions' => Purchase::where('payment_status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'activities' => $activities,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        $filename = 'transaction-report-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.xlsx';

        if ($request->boolean('download')) {
            return response($this->buildTransactionsReportXlsx($data))
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'max-age=0');
        }

        return view('admin.reports.export-transactions-excel-preview', $data);
    }

    private function buildUsersReportXlsx(array $data): string
    {
        $rows = [
            [['value' => 'USER REPORT', 'style' => 1]],
            [['value' => 'EBOOK STORE SYSTEM', 'style' => 2]],
            [['value' => 'Period: ' . $data['startDate']->format('d F Y') . ' - ' . $data['endDate']->format('d F Y'), 'style' => 2]],
            [],
            [['value' => 'Total Users', 'style' => 3], ['value' => $data['totalUsers'], 'type' => 'number', 'style' => 4], [], ['value' => 'Users Active in Period', 'style' => 3], ['value' => $data['newUsers'], 'type' => 'number', 'style' => 4]],
            [],
            [['value' => 'No', 'style' => 5], ['value' => 'Name', 'style' => 5], ['value' => 'Email', 'style' => 5], ['value' => 'Last Login', 'style' => 5]],
        ];

        foreach ($data['userRecords'] as $index => $user) {
            $rows[] = [
                ['value' => $index + 1, 'type' => 'number'],
                ['value' => $user->name],
                ['value' => $user->email],
                ['value' => ($user->last_login_at ?? $user->created_at)->format('d/m/Y H:i')],
            ];
        }

        if ($data['userRecords']->isEmpty()) {
            $rows[] = [['value' => 'No user records found for this period.', 'style' => 2]];
        }

        $merges = ['A1:E1', 'A2:E2', 'A3:E3'];

        if ($data['userRecords']->isEmpty()) {
            $merges[] = 'A8:E8';
        }

        $worksheetOptions = [
            'columns' => [7, 28, 36, 22, 16],
            'merges' => $merges,
            'freezePane' => 'A8',
            'autoFilter' => 'A7:D' . max(7, count($rows)),
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

    private function buildEbookReportXlsx(array $data): string
    {
        $merges = ['A1:E1', 'A2:E2', 'A3:E3'];
        $rows = [
            [['value' => 'EBOOK REPORT', 'style' => 1]],
            [['value' => 'EBOOK STORE SYSTEM', 'style' => 2]],
            [['value' => 'Period: ' . $data['startDate']->format('d F Y') . ' - ' . $data['endDate']->format('d F Y'), 'style' => 2]],
            [],
            [['value' => 'Paid Ebooks Sold', 'style' => 3], ['value' => $data['totalPaidEbooks'], 'type' => 'number', 'style' => 4], ['value' => 'Free Ebooks', 'style' => 3], ['value' => $data['totalFreeEbooks'], 'type' => 'number', 'style' => 4]],
            [],
            [['value' => 'No', 'style' => 5], ['value' => 'Ebook', 'style' => 5], ['value' => 'Category', 'style' => 5], ['value' => 'Sales/Reads', 'style' => 5], ['value' => 'Revenue', 'style' => 5]],
        ];

        foreach ($data['topSellingEbooks'] as $index => $ebook) {
            $rows[] = [
                ['value' => $index + 1, 'type' => 'number'],
                ['value' => $ebook->title],
                ['value' => $ebook->category],
                ['value' => $ebook->sales_count, 'type' => 'number'],
                ['value' => $ebook->revenue, 'type' => 'number'],
            ];
        }

        if ($data['topSellingEbooks']->isEmpty()) {
            $rows[] = [['value' => 'No ebook sales found for this period.', 'style' => 2]];
            $merges[] = 'A8:E8';
        }

        $topTableLastRow = 7 + max(1, $data['topSellingEbooks']->count());
        $rows[] = [];
        $categoryTitleRow = count($rows) + 1;
        $rows[] = [['value' => 'Category Breakdown', 'style' => 3]];
        $rows[] = [['value' => 'Category', 'style' => 5], ['value' => 'Total', 'style' => 5]];
        $merges[] = 'A' . $categoryTitleRow . ':E' . $categoryTitleRow;

        foreach ($data['categoryStats'] as $category) {
            $rows[] = [
                ['value' => $category->category],
                ['value' => $category->total, 'type' => 'number'],
            ];
        }

        if ($data['categoryStats']->isEmpty()) {
            $rows[] = [['value' => 'No category breakdown available.', 'style' => 2]];
            $merges[] = 'A' . count($rows) . ':E' . count($rows);
        }

        $worksheetOptions = [
            'columns' => [7, 42, 22, 16, 18],
            'merges' => $merges,
            'freezePane' => 'A8',
            'autoFilter' => 'A7:E' . $topTableLastRow,
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

    private function buildTransactionsReportXlsx(array $data): string
    {
        $merges = ['A1:H1', 'A2:H2', 'A3:H3'];
        $rows = [
            [['value' => 'TRANSACTION REPORT', 'style' => 1]],
            [['value' => 'EBOOK STORE SYSTEM', 'style' => 2]],
            [['value' => 'Period: ' . $data['startDate']->format('d F Y') . ' - ' . $data['endDate']->format('d F Y'), 'style' => 2]],
            [],
            [['value' => 'Total Transactions', 'style' => 3], ['value' => $data['totalTransactions'], 'type' => 'number', 'style' => 4], ['value' => 'Paid', 'style' => 3], ['value' => $data['paidTransactions'], 'type' => 'number', 'style' => 4], ['value' => 'Pending', 'style' => 3], ['value' => $data['pendingTransactions'], 'type' => 'number', 'style' => 4]],
            [],
            [['value' => 'No', 'style' => 5], ['value' => 'Date', 'style' => 5], ['value' => 'User', 'style' => 5], ['value' => 'Email', 'style' => 5], ['value' => 'Ebook', 'style' => 5], ['value' => 'Activity', 'style' => 5], ['value' => 'Status', 'style' => 5], ['value' => 'Amount', 'style' => 5]],
        ];

        foreach ($data['activities'] as $index => $activity) {
            $status = $activity->purchase?->payment_status ?: 'N/A';
            $amount = $activity->amount ?? $activity->ebook?->price;
            $rows[] = [
                ['value' => $index + 1, 'type' => 'number'],
                ['value' => $activity->created_at->format('d/m/Y H:i')],
                ['value' => $activity->user?->name ?? '-'],
                ['value' => $activity->user?->email ?? '-'],
                ['value' => $activity->ebook?->title ?? '-'],
                ['value' => ucfirst(str_replace('_', ' ', $activity->activity_type))],
                ['value' => ucfirst($status)],
                ['value' => $amount ?? 0, 'type' => 'number'],
            ];
        }

        if ($data['activities']->isEmpty()) {
            $rows[] = [['value' => 'No transaction activities found for this period.', 'style' => 2]];
            $merges[] = 'A8:H8';
        }

        $worksheetOptions = [
            'columns' => [7, 18, 26, 34, 42, 20, 16, 16],
            'merges' => $merges,
            'freezePane' => 'A8',
            'autoFilter' => 'A7:H' . max(7, count($rows)),
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

    private function resolvePeriod(Request $request): array
    {
        $period = $request->get('period', '30days');
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

        return [$period, $startDate, $endDate];
    }

    private function monthLabels(Carbon $startDate, Carbon $endDate): \Illuminate\Support\Collection
    {
        $labels = collect();
        $cursor = $startDate->copy()->startOfMonth();
        $lastMonth = $endDate->copy()->startOfMonth();

        while ($cursor->lte($lastMonth)) {
            $labels[$cursor->format('Y-m')] = $cursor->format('M Y');
            $cursor->addMonth();
        }

        return $labels;
    }
}
