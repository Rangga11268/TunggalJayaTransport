import Swal from 'sweetalert2';

window.Swal = Swal;

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
});