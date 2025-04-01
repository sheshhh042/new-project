import Chart from 'chart.js/auto';

document.addEventListener("DOMContentLoaded", function () {
    console.log("Chart.js loaded:", typeof Chart !== "undefined");

    // Department Research Chart
    const deptCanvas = document.getElementById('departmentChart');
    if (deptCanvas) {
        const ctxDepartment = deptCanvas.getContext('2d');

        const labels = JSON.parse(deptCanvas.getAttribute('data-labels'));
        const data = JSON.parse(deptCanvas.getAttribute('data-data'));

        if (labels.length === 0 || data.length === 0) {
            console.error("No data for department chart!");
            return;
        }

        new Chart(ctxDepartment, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Research',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(199, 199, 199, 0.2)',
                        'rgba(83, 102, 255, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)',
                        'rgba(83, 102, 255, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Allow the chart to resize dynamically
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                },
                layout: {
                    padding: 10 // Add padding around the chart
                }
            }
        });
    } else {
        console.error("Canvas for department chart not found.");
    }
});