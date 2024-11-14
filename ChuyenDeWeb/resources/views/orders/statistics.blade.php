@extends('layouts.dashboard')
@section('content')
<div class="container-fluid">
    <!-- Bộ lọc thời gian -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('orders.statistics') }}" class="row">
                <div class="col-md-4">
                    <label>Từ ngày:</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-4">
                    <label>Đến ngày:</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4>{{ number_format($overview['total_orders']) }}</h4>
                    <div>Tổng đơn hàng</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4>{{ number_format($overview['total_revenue']) }}đ</h4>
                    <div>Doanh thu</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h4>{{ number_format($overview['average_order_value']) }}đ</h4>
                    <div>Giá trị đơn trung bình</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h4>{{ number_format($overview['total_customers']) }}</h4>
                    <div>Số khách hàng</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thống kê theo trạng thái -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Thống kê theo trạng thái
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Trạng thái</th>
                                <th>Số đơn</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statusStats as $stat)
                            <tr>
                                <td>{{ ucfirst($stat->status) }}</td>
                                <td>{{ number_format($stat->total) }}</td>
                                <td>{{ number_format($stat->revenue) }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top sản phẩm bán chạy -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Top sản phẩm bán chạy
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                            <tr>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ number_format($product->total_quantity) }}</td>
                                <td>{{ number_format($product->total_revenue) }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ thống kê theo ngày -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-line me-1"></i>
            Thống kê theo ngày
        </div>
        <div class="card-body">
            <canvas id="dailyStatsChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dữ liệu cho biểu đồ
    const dailyStats = @json($dailyStats);
    
    // Chuẩn bị dữ liệu
    const labels = dailyStats.map(stat => stat.date);
    const orderData = dailyStats.map(stat => stat.total_orders);
    const revenueData = dailyStats.map(stat => stat.daily_revenue);

    // Vẽ biểu đồ
    const ctx = document.getElementById('dailyStatsChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Số đơn hàng',
                    data: orderData,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'Doanh thu (VNĐ)',
                    data: revenueData,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection