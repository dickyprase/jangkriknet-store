// Chart Bar Sederhana
let barChart = null;

async function initBarChart() {
    try {
        const response = await fetch('get_chart_data.php');
        const data = await response.json();
        
        const ctx = document.getElementById('myBarChart');
        if (barChart) {
            barChart.destroy();
        }
        
        barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.bar.labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data.bar.data,
                    backgroundColor: '#0d6efd',
                    borderColor: '#0d6efd',
                    borderWidth: 1
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
document.addEventListener('DOMContentLoaded', initBarChart); 