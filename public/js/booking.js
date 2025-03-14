document.addEventListener('DOMContentLoaded', function() {
    // Show loading spinner
    const loadingSpinner = document.getElementById('loadingSpinner');
    function showLoading() {
        loadingSpinner.style.display = 'block';
    }
    function hideLoading() {
        loadingSpinner.style.display = 'none';
    }

    // Add any booking-related JavaScript here
    console.log('Booking script loaded.');

    // Event listeners for approve and reject buttons
    document.querySelectorAll('.btn-success').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.closest('tr').dataset.bookingId; // Assuming the row has a data attribute
            showLoading(); // Show loading spinner
            fetch(`/booking/approve/${bookingId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                hideLoading(); // Hide loading spinner
                if (response.ok) {
                    location.reload(); // Reload the page to see the updated status
                }
            });
        });
    });

    document.querySelectorAll('.btn-danger').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.closest('tr').dataset.bookingId; // Assuming the row has a data attribute
            showLoading(); // Show loading spinner
            fetch(`/booking/reject/${bookingId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                hideLoading(); // Hide loading spinner
                if (response.ok) {
                    location.reload(); // Reload the page to see the updated status
                }
            });
        });
    });
});
