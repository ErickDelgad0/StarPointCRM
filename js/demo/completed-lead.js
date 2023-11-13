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
                    autoSkip: false,
                    maxRotation: 90,
                    minRotation: 90
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    autoSkip: false
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

// Top 5 producers
function showTopProducers() {
    // Clone the current chart data
    var currentData = leadsChart.data.datasets[0].data.slice();
    var currentLabels = leadsChart.data.labels.slice();

    // Combine labels and data, sort them, and slice the top 5
    var combinedData = currentLabels.map((label, i) => ({ label: label, value: currentData[i] }));
    combinedData.sort((a, b) => b.value - a.value);
    var topData = combinedData.slice(0, 5);

    // Update chart
    setChartLabels(topData.map(item => item.label));
    setChartData(topData.map(item => item.value));
    setChartLabel('Top 5 Producers');
}

// Bottom 5 producers
function showBottomProducers() {
    var currentData = leadsChart.data.datasets[0].data.slice();
    var currentLabels = leadsChart.data.labels.slice();

    var combinedData = currentLabels.map((label, i) => ({ label: label, value: currentData[i] }));
    combinedData.sort((a, b) => a.value - b.value);
    var bottomData = combinedData.slice(0, 5);

    // Update chart
    setChartLabels(bottomData.map(item => item.label));
    setChartData(bottomData.map(item => item.value));
    setChartLabel('Bottom 5 Producers');
}

