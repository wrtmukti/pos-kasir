<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesSummary;
use App\Models\Category;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class SalesSummaryController extends Controller
{
    // Contoh laporan per kategori

    /**
     * 📊 Index - Laporan ringkas semua kategori
     */
    public function index()
    {
        $report = SalesSummary::select(
            'category_id',
            DB::raw('SUM(quantity_sold) as total_sold'),
            DB::raw('SUM(total_revenue) as total_income')
        )
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return view('admin.operator.report_sale.index', compact('report'));
    }

    /**
     * 📅 Harian
     */
    public function daily(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $data = SalesSummary::with('category')
            ->select(
                'category_id',
                DB::raw('SUM(quantity_sold) as total_qty'),
                DB::raw('SUM(total_revenue) as total_revenue')
            )
            ->whereDate('transaction_date', $date)
            ->groupBy('category_id')
            ->get();

        $totalRevenue = $data->sum('total_revenue');

        return view('admin.operator.report_sale.daily', compact('data', 'date', 'totalRevenue'));
    }

    /**
     * 📆 Mingguan
     */
    public function weekly(Request $request)
    {
        // Ambil tanggal acuan (default: hari ini)
        $currentDate = $request->date ? Carbon::parse($request->date) : Carbon::today();

        // Hitung periode minggu yang memuat tanggal itu
        $startOfWeek = $currentDate->copy()->startOfWeek();
        $endOfWeek   = $currentDate->copy()->endOfWeek();

        $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
        $categories = Category::all();

        $chartData = [];
        $labels = [];

        foreach ($categories as $category) {
            $chartData[$category->category_name] = [];

            foreach ($period as $date) {
                $total = SalesSummary::where('category_id', $category->id)
                    ->whereDate('transaction_date', $date)
                    ->sum('total_revenue');

                $chartData[$category->category_name][] = $total;
            }
        }

        // Ulangi period karena di-loop di atas (CarbonPeriod tidak reusable)
        $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
        foreach ($period as $date) {
            $labels[] = $date->format('D');
        }

        $totalRevenue = SalesSummary::whereBetween('transaction_date', [$startOfWeek, $endOfWeek])
            ->sum('total_revenue');

        return view('admin.operator.report_sale.weekly', compact(
            'chartData',
            'labels',
            'totalRevenue',
            'startOfWeek',
            'endOfWeek',
            'currentDate'
        ));
    }

    /**
     * 🗓️ Bulanan
     */
    public function monthly(Request $request)
    {
        // Ambil tanggal acuan (default: hari ini)
        $currentDate = $request->date ? Carbon::parse($request->date) : Carbon::today();

        // Hitung periode bulan aktif
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth   = $currentDate->copy()->endOfMonth();

        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
        $categories = Category::all();

        $chartData = [];
        $labels = [];

        foreach ($categories as $category) {
            $chartData[$category->category_name] = [];

            foreach ($period as $date) {
                $total = SalesSummary::where('category_id', $category->id)
                    ->whereDate('transaction_date', $date)
                    ->sum('total_revenue');

                $chartData[$category->category_name][] = $total;
            }
        }

        // Ulangi lagi period karena di atas udah di-loop
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
        foreach ($period as $date) {
            $labels[] = $date->format('d M');
        }

        $totalRevenue = SalesSummary::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('total_revenue');

        return view('admin.operator.report_sale.monthly', compact(
            'chartData',
            'labels',
            'totalRevenue',
            'startOfMonth',
            'endOfMonth',
            'currentDate'
        ));
    }

    /**
     * 📈 Tahunan
     */
    public function yearly(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;
        $categories = Category::all();
        $chartData = [];

        foreach ($categories as $category) {
            $chartData[$category->category_name] = [];

            for ($month = 1; $month <= 12; $month++) {
                $total = SalesSummary::where('category_id', $category->id)
                    ->whereYear('transaction_date', $year)
                    ->whereMonth('transaction_date', $month)
                    ->sum('total_revenue');

                $chartData[$category->category_name][] = $total;
            }
        }

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $totalRevenue = SalesSummary::whereYear('transaction_date', $year)->sum('total_revenue');

        return view('admin.operator.report_sale.yearly', compact('chartData', 'labels', 'totalRevenue', 'year'));
    }
}
