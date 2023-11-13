// Assuming you have a canvas element for the pie chart in your HTML
var ctxPie = document.getElementById('leadsPieChart').getContext('2d');

// Initialize the Pie Chart
var leadsPieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: [],
        datasets: [{
            label: 'Leads by Team',
            data: [],
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(255, 159, 64, 0.6)'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        // Pie chart-specific options
        responsive: true,
        maintainAspectRatio: false
    }
});

// Functions to update the Pie Chart
function setPieChartLabels(labels) {
    leadsPieChart.data.labels = labels;
    leadsPieChart.update();
}

function setPieChartData(data) {
    leadsPieChart.data.datasets[0].data = data;
    leadsPieChart.update();
}
