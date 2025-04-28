<!-- Charts -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Monthly Revenue Chart -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Revenue</h3>
            <canvas id="monthlyRevenueChart" class="w-full" height="300"></canvas>
        </div>
    </div>

    <!-- Wool Quality Distribution Chart -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Wool Quality Distribution</h3>
            <canvas id="woolQualityChart" class="w-full" height="300"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Revenue Chart
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyRevenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($monthlyRevenue)) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode(array_values($monthlyRevenue)) !!},
                borderColor: '#4F46E5',
                tension: 0.3,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Wool Quality Distribution Chart
    const woolQualityCtx = document.getElementById('woolQualityChart').getContext('2d');
    new Chart(woolQualityCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($woolQualityDistribution)) !!},
            datasets: [{
                label: 'Number of Batches',
                data: {!! json_encode(array_values($woolQualityDistribution)) !!},
                backgroundColor: '#4F46E5',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush 