// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"],
    datasets: [{
      label: "Pendapatan",
      lineTension: 0.3,
      backgroundColor: "rgba(2,117,216,0.2)",
      borderColor: "rgba(2,117,216,1)",
      pointRadius: 5,
      pointBackgroundColor: "rgba(2,117,216,1)",
      pointBorderColor: "rgba(255,255,255,0.8)",
      pointHoverRadius: 5,
      pointHoverBackgroundColor: "rgba(2,117,216,1)",
      pointHitRadius: 50,
      pointBorderWidth: 2,
      data: [500000, 750000, 600000, 900000, 800000, 1200000, 1000000],
    }],
  },
  options: {
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 1500000,
          maxTicksLimit: 5,
          callback: function(value) {
            return 'Rp ' + value.toLocaleString('id-ID');
          }
        },
        gridLines: {
          color: "rgba(0, 0, 0, .125)",
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      callbacks: {
        label: function(tooltipItem, data) {
          return 'Rp ' + tooltipItem.yLabel.toLocaleString('id-ID');
        }
      }
    }
  }
});

// Chart Line Sederhana
let lineChart = null;

async function initLineChart() {
    try {
        const response = await fetch('get_chart_data.php');
        const data = await response.json();
        
        const ctx = document.getElementById('purchasesChart');
        if (lineChart) {
            lineChart.destroy();
        }
        
        lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.purchases.labels,
                datasets: [{
                    label: 'Pesanan',
                    data: data.purchases.data,
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
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
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
