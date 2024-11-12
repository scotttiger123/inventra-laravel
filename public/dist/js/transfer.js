$(function () {
    $('#transfer-listings').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true
    });
});

  document.addEventListener('DOMContentLoaded', function () {
        // Set default date to today's date in YYYY-MM-DD format
        const dateInput = document.getElementById('datepicker');
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0]; // Get date in 'YYYY-MM-DD' format
        dateInput.value = formattedDate;
    });
//Date picker
$('#datepicker').datepicker({
    autoclose: true
  })

  //iCheck for checkbox and radio inputs
  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass   : 'iradio_minimal-blue'
  })
  //Red color scheme for iCheck
  $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
    checkboxClass: 'icheckbox_minimal-red',
    radioClass   : 'iradio_minimal-red'
  })
  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })

  //Colorpicker
  $('.my-colorpicker1').colorpicker()
  //color picker with addon
  $('.my-colorpicker2').colorpicker()

  //Timepicker
  $('.timepicker').timepicker({
    showInputs: false
  })


$('#viewTransferModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget); // Button that triggered the modal
    const fromWarehouse = button.data('from-warehouse');
    const toWarehouse = button.data('to-warehouse');
    const quantity = button.data('quantity');
    const date = button.data('date');

    const modal = $(this);
    modal.find('#from-warehouse').text(fromWarehouse);
    modal.find('#to-warehouse').text(toWarehouse);
    modal.find('#quantity').text(quantity);
    modal.find('#transfer-date').text(date);
});

function confirmDeleteTransfer(transferId) {
    if (confirm('Are you sure you want to delete this transfer?')) {
        const form = document.getElementById(`deleteForm-${transferId}`);
        const formData = new FormData(form);

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
                alert('Transfer deleted successfully!');
                document.getElementById(`transferRow-${transferId}`).remove();
            } else {
                alert('Failed to delete transfer.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the transfer.');
        });
    }
}
