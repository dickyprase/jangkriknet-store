// Chart Line Sederhana
let lineChart = null;

async function initLineChart() {
    try {
        const response = await fetch('get_chart_data.php');
        const data = await response.json();
        
        const ctx = document.getElementById('myAreaChart');
        if (lineChart) {
            lineChart.destroy();
        }
        
        lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.line.labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data.line.data,
                    fill: true,
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderColor: '#0d6efd',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading chart data:', error);
    }
}

// Inisialisasi chart saat halaman dimuat
document.addEventListener('DOMContentLoaded', initLineChart); 