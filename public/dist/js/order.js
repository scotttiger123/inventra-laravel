
// Handle the form submission with AJAX
document.getElementById('create-customer-form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission behavior

    const form = this;  // Get the form element
    const formData = new FormData(form);  // Create FormData object from the form
     // Hide messages before submitting the form
     document.getElementById('success-message-customer-save').style.display = 'none';
     document.getElementById('error-message-customer-save').style.display = 'none';
    // Send the form data using AJAX (fetch)
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',  // Set CSRF token (for Laravel)
            'X-Requested-With': 'XMLHttpRequest'  // Indicate that it's an AJAX request
        },
        body: formData  // Attach the form data to the request
    })
    .then(response => response.json())  // Parse the JSON response
    .then(data => {
        console.log(data);
        // Check if the request was successful
        if (data.success) {
            document.getElementById('success-message-customer-save').style.display = 'block';
            document.getElementById('success-message-customer-save').innerText = data.message;
            form.reset();  // Reset the form after successful submission (optional)
            // Append the new customer to the dropdown
             // Check if customer data exists before appending
        if (data.customer_name && data.customer_id) {
            // Append the new customer to the customer dropdown
            const customerNamesList = document.getElementById('customer-names');
            const newOption = document.createElement('option');
            newOption.value = data.customer_name; // assuming the server response contains the customer name
            newOption.setAttribute('data-id', data.customer_id); // assuming the server response contains the customer ID
            customerNamesList.appendChild(newOption);

            // Set the input field value to the new customer's name
            document.getElementById('customer-name-input').value = data.customer_name;
            document.getElementById('customer-id').value = data.customer_id;
        } else {
            console.error('Missing customer data in response');
        }

        } else {
            document.getElementById('error-message-customer-save').style.display = 'block';
            document.getElementById('error-message-customer-save').innerHTML = data.message;
        }
    })
    .catch(error => {
        // Handle any errors that occurred during the fetch
        console.error("Error:", error);
        document.getElementById('error-message-customer-save').style.display = 'block';
        document.getElementById('error-message-customer-save').innerText = "An unexpected error occurred. Please try again later.";
    });
});




// Custoemr name validation & get id 
const customerNameInput = document.getElementById('customer-name-input');
const customerIdField = document.getElementById('customer-id');


customerNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#customer-names option[value="${customerNameInput.value}"]`);
    if (selectedOption) {
        const customerId = selectedOption.getAttribute('data-id');
        customerIdField.value = customerId;
    } else {
        customerIdField.value = ''; // Clear customer ID if no valid selection
    }
});


// Salesperson name validation & get id
const salespersonNameInput = document.getElementById('salesperson-name-input');
const salespersonIdField = document.getElementById('salesperson-id');

salespersonNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#salesperson-names option[value="${salespersonNameInput.value}"]`);
    if (selectedOption) {
        const salespersonId = selectedOption.getAttribute('data-id');
        salespersonIdField.value = salespersonId;
    } else {
        salespersonIdField.value = ''; // Clear salesperson ID if no valid selection
    }
});





//Savings order
document.getElementById('submitOrder').addEventListener('click', function () {
        // Get the form element
        const form = document.getElementById('orderForm');
        const formData = new FormData(form); // Create FormData object from the form
        
        
        var orderData = getOrderData();  // Get the order data
        formData.append('orderData', JSON.stringify(orderData));  // Add the order data as JSON
        

        fetch(form.action, {  // Use form.action to get the URL from the form's action attribute
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',  // Set CSRF token
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server responded with status ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Handle the success or error response
            if (data.success) {
                document.getElementById('success-message').style.display = 'block';
                document.getElementById('success-message').innerText = data.message;
                form.reset(); // Optionally reset the form
            } else {
                document.getElementById('error-message').style.display = 'block';
                document.getElementById('error-message').innerHTML = data.message;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById('error-message').style.display = 'block';
            document.getElementById('error-message').innerText = "An unexpected error occurred. Please try again later.";
        });
    });


function getOrderData() {
    var orderData = [];  // Array to store the order items data
    
    // Get the table rows
    var rows = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0].rows;

    // Loop through each row and get the data
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var product = row.cells[0].innerText;  // Product name
        var qty = row.cells[1].querySelector('input').value || 0;  // Quantity input value
        var rate = parseFloat(row.cells[2].innerText);  // Rate
        var discountType = row.cells[3].innerText;  // Discount type (percentage or flat)
        var discountValue = parseFloat(row.cells[3].innerText) || 0;  // Discount value
        var netRate = parseFloat(row.cells[4].innerText);  // Net rate after discount
        var amount = parseFloat(row.cells[5].innerText);  // Amount after discount

        // Add the item data to the orderData array
        orderData.push({
            product: product,
            qty: qty,
            rate: rate,
            discountType: discountType,
            discountValue: discountValue,
            netRate: netRate,
            amount: amount
        });
    }

    return orderData;  // Return the collected order data
}















    function addItemToOrder() {
        var product = document.getElementById('Product').value;
        var qty = parseFloat(document.getElementById('qty_id').value) || 0;  // Default to 0 if empty
        var rate = parseFloat(document.getElementById('Rate_id').value);
        var discountType = document.getElementById('discount_type').value;
        var discountValue = parseFloat(document.getElementById('discount_value').value);

        var amount = qty * rate;
        var discountAmount = 0;

        // Apply discount based on discount type
        if (discountType === "percentage" && discountValue > 0) {
            discountAmount = (amount * discountValue) / 100;
        } else if (discountType === "flat" && discountValue > 0) {
            discountAmount = discountValue;
        }

        amount -= discountAmount;
        var netRate = rate - discountAmount;

        // Round values to 2 decimal places
        netRate = netRate.toFixed(2);
        amount = amount.toFixed(2);
        discountAmount = discountAmount.toFixed(2);

        var table = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow();

        // Create the table cells
        var cell1 = newRow.insertCell(0); // Product
        var cell2 = newRow.insertCell(1); // Quantity (editable)
        var cell3 = newRow.insertCell(2); // Rate
        var cell4 = newRow.insertCell(3); // Discount
        var cell5 = newRow.insertCell(4); // Net Rate
        var cell6 = newRow.insertCell(5); // Amount
        var cell7 = newRow.insertCell(6); // Delete button

        // Set product name
        cell1.innerHTML = product;

        // Create editable input for quantity and add it to cell2
        var input = createEditableQuantityCell(qty);  // Pass the initial qty value
        cell2.appendChild(input); // Append the input to the cell

        // Set rate
        cell3.innerHTML = rate.toFixed(2);

        // Set discount information
        if (discountType === 'percentage' && discountValue > 0) {
            cell4.innerHTML = discountValue.toFixed(2) + '%';
        } else if (discountType === 'flat' && discountValue > 0) {
            cell4.innerHTML = discountValue.toFixed(2);
        } else {
            cell4.innerHTML = '-';
        }

        // Set net rate and amount
        cell5.innerHTML = netRate;
        cell6.innerHTML = amount;

        // Create delete button
        var deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.innerHTML = "Delete";
        deleteButton.classList.add("btn", "btn-danger", "btn-sm");
        deleteButton.onclick = function() { removeItem(deleteButton); };
        cell7.appendChild(deleteButton);

        // Clear the input fields after adding the item
        document.getElementById('Product').value = '';
        document.getElementById('qty_id').value = '';
        document.getElementById('Rate_id').value = '';
        document.getElementById('discount_value').value = '';
        document.getElementById('uom_id').value = '';
        document.getElementById('sale_note').value = '';
        document.getElementById('staff_note').value = '';

        recalculateTotals();
    }

    function createEditableQuantityCell(qty) {
        // Create the input element
        var input = document.createElement('input');
        input.type = 'number';
        input.classList.add('form-control', 'form-control-sm');
        input.value = qty || '';  // Set the initial value of qty (fallback to 12 if undefined)

        input.min = '0';  // Optional: Set a minimum value for the input
        input.step = '0.01';  // Optional: Set step increment for the input

        // Event listener for when the quantity is changed
        input.addEventListener('input', function() {
            var row = input.closest('tr');  // Get the row containing this input
            var newQty = parseFloat(input.value) || 0;  // Get the new qty, default to 0 if invalid
            var rate = parseFloat(row.cells[2].innerHTML);  // Get the rate from the row
            var discountType = row.cells[3].innerHTML;  // Get the discount type from the row
            var discountValue = parseFloat(row.cells[3].innerHTML) || 0;  // Get the discount value from the row

            // Calculate the new amount and net rate based on the new quantity
            var amount = newQty * rate;
            var discountAmount = 0;
            if (discountType === 'percentage' && discountValue > 0) {
                discountAmount = (amount * discountValue) / 100;
            } else if (discountType === 'flat' && discountValue > 0) {
                discountAmount = discountValue;
            }

            // Update amount and net rate after applying the discount
            amount -= discountAmount;
            var netRate = rate - discountAmount;

            // Update the row with the new values
            row.cells[4].innerHTML = netRate.toFixed(2);  // Update the net rate cell
            row.cells[5].innerHTML = amount.toFixed(2);   // Update the amount cell

            recalculateTotals();  // Recalculate the totals after quantity change
        });

        return input;
    }





    // Function to recalculate the totals (you can modify this based on your exact needs)
    function recalculateTotals() {
        var table = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
        var rows = table.rows;
        var grossAmount = 0;

        for (var i = 0; i < rows.length; i++) {
            var amount = parseFloat(rows[i].cells[5].innerHTML);
            grossAmount += amount;
        }

        // Update the total fields
        document.getElementById('GTotal').value = grossAmount.toFixed(2);
        document.getElementById('NeAmount_id').value = grossAmount.toFixed(2);
        document.getElementById('Balance_id').value = grossAmount.toFixed(2);
    }

    // Function to remove an item from the table
    function removeItem(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
        recalculateTotals();
    }
