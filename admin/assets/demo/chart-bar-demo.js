// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
    datasets: [{
      label: "Pendapatan",
      backgroundColor: "rgba(2,117,216,1)",
      borderColor: "rgba(2,117,216,1)",
      data: [1500000, 2000000, 1800000, 2500000, 2200000, 3000000, 2800000, 3200000, 2900000, 3500000, 3300000, 4000000],
    }],
  },
  options: {
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 6
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 5000000,
          maxTicksLimit: 5,
          callback: function(value) {
            return 'Rp ' + value.toLocaleString('id-ID');
          }
        },
        gridLines: {
          display: true
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

// Chart Bar Sederhana
let barChart = null;

async function initBarChart() {
    try {
        const response = await fetch('get_chart_data.php');
        const data = await response.json();
        
        const ctx = document.getElementById('salesChart');
        if (barChart) {
            barChart.destroy();
        }
        
        barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.sales.labels,
                datasets: [{
                    label: 'Produk',
                    data: data.sales.data,
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
document.addEventListener('DOMContentLoaded', initBarChart);
