<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class AdminDashboarController extends Controller
{
    public function index()
    {
        $countMenu = Menu::count();
        $countUser = User::where('role', 'user')->count();
        $countOngoingOrders = Pesanan::whereIn('status', ['dibayar', 'diantar'])->count();
        $countCompletedOrders = Pesanan::where('status', 'selesai')->count();

        return view('admin.dashboard', compact(
            'countMenu',
            'countUser',
            'countOngoingOrders',
            'countCompletedOrders'
        ));
    }

    public function getMonthlyStatistics(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        return $this->getStatistics($startDate, $endDate, 'monthly');
    }

    public function getYearlyStatistics(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = $startDate->copy()->endOfYear();

        return $this->getStatistics($startDate, $endDate, 'yearly');
    }

    private function getStatistics($startDate, $endDate, $type)
    {
        // Revenue
        $revenue = Pesanan::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'selesai')
            ->sum('total_harga');

        // Comparison with previous period
        $previousStartDate = ($type === 'monthly') ? $startDate->copy()->subMonth() : $startDate->copy()->subYear();
        $previousEndDate = ($type === 'monthly') ? $endDate->copy()->subMonth() : $endDate->copy()->subYear();
        $previousRevenue = Pesanan::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', 'selesai')
            ->sum('total_harga');

        $percentageChange = $previousRevenue > 0
            ? (($revenue - $previousRevenue) / $previousRevenue) * 100
            : 100;

        // Sales data (replacing orderStatistics)
        $salesData = $this->getSalesData($startDate, $endDate, $type);

        // Top Selling Products
        $topSellingProducts = $this->getTopSellingProducts($startDate, $endDate);

        return response()->json([
            'revenue' => $revenue,
            'percentageChange' => round($percentageChange, 2),
            'salesData' => $salesData,
            'topSellingProducts' => $topSellingProducts
        ]);
    }

    private function getSalesData($startDate, $endDate, $type)
    {
        $query = Pesanan::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'selesai');

        if ($type === 'monthly') {
            $query->selectRaw('DATE(created_at) as date, SUM(total_harga) as total_sales')
                ->groupBy('date')
                ->orderBy('date');
        } else { // yearly
            $query->selectRaw('MONTH(created_at) as month, SUM(total_harga) as total_sales')
                ->groupBy('month')
                ->orderBy('month');
        }

        $salesData = $query->get();

        $labels = [];
        $data = [];

        foreach ($salesData as $sale) {
            if ($type === 'monthly') {
                $labels[] = $sale->date;
            } else {
                $labels[] = Carbon::create()->month($sale->month)->format('F');
            }
            $data[] = $sale->total_sales;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getTopSellingProducts($startDate, $endDate)
    {
        return Menu::join('jenis_menu', 'menu.id', '=', 'jenis_menu.menu_id')
            ->join('detail_pesanan', 'jenis_menu.id', '=', 'detail_pesanan.jenis_menu_id')
            ->join('pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            ->whereBetween('pesanan.created_at', [$startDate, $endDate])
            ->where('pesanan.status', 'selesai')
            ->groupBy('menu.id', 'menu.nama', 'menu.foto')
            ->selectRaw('menu.id, menu.nama, menu.foto, SUM(detail_pesanan.jumlah) as total_sold')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
    }
}