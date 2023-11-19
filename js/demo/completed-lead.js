// Get the canvas context
var all_ctx = document.getElementById('leadsChart').getContext('2d');
var bottom_ctx = document.getElementById('bottomLeadsChart').getContext('2d');
var top_ctx = document.getElementById('topLeadsChart').getContext('2d');

var all_label = 'Leads Completed';
var bottom_label = 'Bottom Leads Completed';
var top_label = 'Top Leads Completed';

var leadsChart = new Chart(all_ctx, {
    type: 'bar',
    data: {
        labels: [],
        datasets: [{
            label: all_label,
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
                    autoSkip: true,
                    maxRotation: 0,
                    minRotation: 0
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
        maintainAspectRatio: false
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




var bottomLeadsChart = new Chart(bottom_ctx, {
    type: 'bar',
    data: {
        labels: [],
        datasets: [{
            label: bottom_label,
            data: [],
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
                    autoSkip: true,
                    maxRotation: 0,
                    minRotation: 0
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
        maintainAspectRatio: false
    }
});

// Functions to set labels and data for the top leads chart
function setBottomLeadsChartLabels(labels) {
    bottomLeadsChart.data.labels = labels.slice(0, 5); // Show only the first 5 labels
    bottomLeadsChart.update();
}

function setBottomLeadsChartData(data) {
    bottomLeadsChart.data.datasets[0].data = data.slice(0, 5); // Show only the first 5 data points
    bottomLeadsChart.update();
}

function setBottomLeadsChartLabel(text) {
    bottomLeadsChart.data.datasets[0].label = text;
    bottomLeadsChart.update();
}


var topLeadsChart = new Chart(top_ctx, {
    type: 'bar',
    data: {
        labels: [],
        datasets: [{
            label: top_label,
            data: [],
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
                    autoSkip: true,
                    maxRotation: 0,
                    minRotation: 0
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
        maintainAspectRatio: false
    }
});

// Functions to set labels and data for the top leads chart
function setTopLeadsChartLabels(labels) {
    topLeadsChart.data.labels = labels.slice(-5); // Show only the first 5 labels
    topLeadsChart.update();
}

function setTopLeadsChartData(data) {
    topLeadsChart.data.datasets[0].data = data.slice(-5); // Show only the first 5 data points
    topLeadsChart.update();
}

function setTopLeadsChartLabel(text) {
    topLeadsChart.data.datasets[0].label = text;
    topLeadsChart.update();
}
