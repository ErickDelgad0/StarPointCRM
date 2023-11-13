// Get the canvas context
var ctx = document.getElementById('leadsChart').getContext('2d');
var label = 'Leads Completed';

// Initialize the chart
var leadsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [], // To be filled dynamically
        datasets: [{
            label: label,
            data: [], // To be filled dynamically
            backgroundColor: 'rgba(0, 123, 255, 0.5)',
            borderColor: 'rgba(0, 123, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    autoSkip: true, // Automatically skip labels to avoid overlap
                    maxRotation: 0, // Optional: Set to 0 to prevent rotation of labels
                    minRotation: 0  // Optional: Set to 0 to prevent rotation of labels
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    autoSkip: true
                }
            }
        },        
        plugins: {
            title: {
                display: false
            },
            legend: {
                display: false
            }
        },
        responsive: true,
        maintainAspectRatio: false // Allows the chart to resize based on container
    }
});

// Functions to set labels and data
function setChartLabels(labels) {
    leadsChart.data.labels = labels;
    leadsChart.update();
}

function setChartData(data) {
    leadsChart.data.datasets[0].data = data;
    leadsChart.update();
}

function setChartLabel(text) {
    leadsChart.data.datasets[0].label = text;
    leadsChart.update();
}
