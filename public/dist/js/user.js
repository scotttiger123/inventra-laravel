$(function () {
    
    $('#user-listings').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false
    });
});

$('#viewUserModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var modal = $(this);
    console.log(button);

    modal.find('#user-name').text(button.data('name') || 'N/A');
    modal.find('#user-email').text(button.data('email') || 'N/A');
    modal.find('#user-phone').text(button.data('phone') || 'N/A');
    modal.find('#user-address').text(button.data('address') || 'N/A');
    modal.find('#user-city').text(button.data('city') || 'N/A');
    modal.find('#user-zip-code').text(button.data('zip-code') || 'N/A');
    modal.find('#user-join-date').text(button.data('join-date') || 'N/A');
    modal.find('#user-role').text(button.data('role') || 'N/A');
    modal.find('#user-status').text(button.data('status') || 'N/A');
});



function confirmDeleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        var form = document.getElementById(`deleteForm-${userId}`);
        var formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User deleted successfully!');
                document.getElementById(`userRow-${userId}`).remove();  // Remove the user row from the table
            } else {
                alert('Failed to delete user.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the user.');
        });
    }
}
