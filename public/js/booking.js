document.addEventListener('DOMContentLoaded', function() {
    // Add any booking-related JavaScript here
    console.log('Booking script loaded.');

    // Event listeners for approve and reject buttons
    document.querySelectorAll('.btn-success').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.closest('tr').dataset.bookingId; // Assuming the row has a data attribute
            fetch(`/booking/approve/${bookingId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    location.reload(); // Reload the page to see the updated status
                }
            });
        });
    });

    document.querySelectorAll('.btn-danger').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.closest('tr').dataset.bookingId; // Assuming the row has a data attribute
            fetch(`/booking/reject/${bookingId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    location.reload(); // Reload the page to see the updated status
                }
            });
        });
    });
});
