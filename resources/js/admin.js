import Swal from 'sweetalert2';
import Chart from 'chart.js/auto';

window.Swal = Swal;
window.Chart = Chart;

// Global configuration for SweetAlert2
const swalConfig = {
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
};

// Function to handle delete confirmations
window.handleDelete = function(formId, title = 'Apakah Anda yakin?', text = 'Tindakan ini tidak dapat dibatalkan.') {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: swalConfig.confirmButtonColor,
        cancelButtonColor: swalConfig.cancelButtonColor,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
};

// Check for success messages and display them with SweetAlert2
document.addEventListener('DOMContentLoaded', function() {
    // Check for create success message
    const createSuccessMessage = document.querySelector('[data-create-success-message]');
    if (createSuccessMessage) {
        const message = createSuccessMessage.getAttribute('data-create-success-message');
        Swal.fire({
            title: 'Berhasil!',
            text: message,
            icon: 'success',
            confirmButtonColor: swalConfig.confirmButtonColor,
            timer: 3000,
            timerProgressBar: true
        });
    }
    
    // Check for success message in session (fallback)
    const successMessage = document.querySelector('[data-success-message]');
    if (successMessage) {
        const message = successMessage.getAttribute('data-success-message');
        Swal.fire({
            title: 'Berhasil!',
            text: message,
            icon: 'success',
            confirmButtonColor: swalConfig.confirmButtonColor,
            timer: 3000,
            timerProgressBar: true
        });
    }
    
    // Check for delete success message
    const deleteSuccessMessage = document.querySelector('[data-delete-success-message]');
    if (deleteSuccessMessage) {
        const message = deleteSuccessMessage.getAttribute('data-delete-success-message');
        Swal.fire({
            title: 'Berhasil Dihapus!',
            text: message,
            icon: 'success',
            confirmButtonColor: swalConfig.confirmButtonColor,
            timer: 3000,
            timerProgressBar: true
        });
    }
    
    // Check for update success message
    const updateSuccessMessage = document.querySelector('[data-update-success-message]');
    if (updateSuccessMessage) {
        const message = updateSuccessMessage.getAttribute('data-update-success-message');
        Swal.fire({
            title: 'Berhasil Diperbarui!',
            text: message,
            icon: 'success',
            confirmButtonColor: swalConfig.confirmButtonColor,
            timer: 3000,
            timerProgressBar: true
        });
    }
    
    // Initialize charts if they exist on the page
    initializeCharts();
});

// Function to initialize charts
function initializeCharts() {
    // Sales chart
    const salesChartElement = document.getElementById('salesChart');
    if (salesChartElement) {
        const salesData = JSON.parse(salesChartElement.getAttribute('data-sales'));
        
        // Prepare data for Chart.js
        const dates = salesData.map(item => item.date);
        const totals = salesData.map(item => item.total);
        
        new Chart(salesChartElement, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Revenue',
                    data: totals,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
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
                                return 'Rp. ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp. ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Occupancy chart
    const occupancyChartElement = document.getElementById('occupancyChart');
    if (occupancyChartElement) {
        const occupancyData = JSON.parse(occupancyChartElement.getAttribute('data-occupancy'));
        
        // Take top 10 for better visualization
        const topOccupancy = occupancyData.slice(0, 10);
        const labels = topOccupancy.map(item => item.bus_name + ' (' + item.route + ')');
        const occupancyRates = topOccupancy.map(item => item.occupancy_rate);
        
        new Chart(occupancyChartElement, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Occupancy Rate (%)',
                    data: occupancyRates,
                    backgroundColor: occupancyRates.map(rate => 
                        rate >= 80 ? 'rgba(34, 197, 94, 0.7)' : 
                        rate >= 60 ? 'rgba(251, 191, 36, 0.7)' : 
                        'rgba(239, 68, 68, 0.7)'
                    ),
                    borderColor: occupancyRates.map(rate => 
                        rate >= 80 ? 'rgb(34, 197, 94)' : 
                        rate >= 60 ? 'rgb(251, 191, 36)' : 
                        'rgb(239, 68, 68)'
                    ),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toFixed(2) + '%';
                            }
                        }
                    }
                }
            }
        });
    }
}