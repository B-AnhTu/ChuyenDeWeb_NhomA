
window.chartData = {
    stats: {
        pending_orders: 0,
        processing_orders: 0,
        shipping_orders: 0,
        completed_orders: 0,
        cancelled_orders: 0,
    },
    monthlyStats: [],
};

// Lấy dữ liệu từ server và gán vào window.chartData
fetch("/orders/statistics")
    .then((response) => response.json())
    .then((data) => {
        window.chartData.stats = {
            pending_orders: data.stats.pending_orders,
            processing_orders: data.stats.processing_orders,
            shipping_orders: data.stats.shipping_orders,
            completed_orders: data.stats.completed_orders,
            cancelled_orders: data.stats.cancelled_orders,
        };
        window.chartData.monthlyStats = data.monthlyStats;
    })
    .catch((error) => {
        console.error("Lỗi khi lấy dữ liệu thống kê:", error);
    });

function initializeOrderStatusChart() {
    const chartElement = document.getElementById("orderStatusChart");
    if (!chartElement) return;

    const stats = window.chartData?.stats || {
        pending_orders: 0,
        processing_orders: 0,
        shipping_orders: 0,
        completed_orders: 0,
        cancelled_orders: 0,
    };

    new Chart(chartElement, {
        type: "pie",
        data: {
            labels: [
                "Chờ xử lý",
                "Đang xử lý",
                "Đang giao",
                "Hoàn thành",
                "Đã hủy",
            ],
            datasets: [
                {
                    data: [
                        stats.pending_orders,
                        stats.processing_orders,
                        stats.shipping_orders,
                        stats.completed_orders,
                        stats.cancelled_orders,
                    ],
                    backgroundColor: [
                        "#ffc107",
                        "#0dcaf0",
                        "#0d6efd",
                        "#198754",
                        "#dc3545",
                    ],
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom",
                },
            },
        },
    });
}

function initializeMonthlyRevenueChart() {
    const chartElement = document.getElementById("monthlyRevenueChart");
    if (!chartElement) return;

    const monthlyStats = window.chartData?.monthlyStats || [];

    new Chart(chartElement, {
        type: "bar",
        data: {
            labels: monthlyStats.map((item) => item?.month || ""),
            datasets: [
                {
                    label: "Doanh thu",
                    data: monthlyStats.map((item) => item?.revenue || 0),
                    backgroundColor: "#0d6efd",
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return formatCurrency(value);
                        },
                    },
                },
            },
        },
    });
}

function formatCurrency(value) {
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
    }).format(value);
}

// Đảm bảo DOM đã load xong
document.addEventListener("DOMContentLoaded", function () {
    // Kiểm tra Chart.js đã load chưa
    if (typeof Chart === "undefined") {
        console.error("Chart.js is not loaded");
        return;
    }

    // Khởi tạo biểu đồ
    initializeOrderStatusChart();
    initializeMonthlyRevenueChart();
});
