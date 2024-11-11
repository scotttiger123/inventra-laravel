@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Create Order</h3>
            <div id="success-message" class="alert alert-success" style="display: none;"></div>
            <div id="error-message" class="alert alert-danger" style="display: none;"></div>
        </div>
        <form id="orderForm">
            @csrf
            <div class="box-body">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Invoice Number *</label>
                        <input type="text" name="invoice_number" id="invoice_number" class="form-control myInput" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Invoice Date *</label>
                        <input type="date" name="invoice_date" id="invoice_date" class="form-control myInput" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Amount *</label>
                        <input type="number" name="amount" id="amount" class="form-control myInput" step="0.01" required>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="button" class="btn btn-primary" id="submitOrder">Create Order</button>
            </div>
        </form>
    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
   document.getElementById('submitOrder').addEventListener('click', function () {
    let formData = new FormData(document.getElementById('orderForm'));

    // Get the CSRF token from the meta tag
    let csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error("CSRF token not found in the meta tag.");
        return;
    }

    fetch("{{ route('orders.store') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: formData
    })
    .then(response => {
        // Check if the response is OK (status code 200)
        if (!response.ok) {
            console.error('Error: Server responded with status ' + response.status);
            return Promise.reject('Server responded with status ' + response.status);
        }

        // Try parsing the response as JSON
        return response.json();
    })
    .then(data => {
        console.log(data);
        if (data.success) {
            document.getElementById('success-message').style.display = 'block';
            document.getElementById('success-message').innerText = data.message;
            document.getElementById('orderForm').reset();
        } else {
            document.getElementById('error-message').style.display = 'block';
            document.getElementById('error-message').innerHTML = data.message;
        }
    })
    .catch(error => {
    console.error("Error:", error);
    // Get the error response text (in case it's HTML or plain text)
    error.response.text().then(text => {
        console.log("Server Response:", text);
    });
    document.getElementById('error-message').style.display = 'block';
    document.getElementById('error-message').innerText = error.message || "An unexpected error occurred. Please try again later.";
});

});


</script>
@endsection
