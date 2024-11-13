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
    input.value = qty || 12;  // Set the initial value of qty (fallback to 12 if undefined)

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
