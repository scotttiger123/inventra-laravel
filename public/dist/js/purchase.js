
    
    function addItemToPurchase() {
        
        // Get selected customer
        var vendorInput = document.getElementById('vendor-id');
        var selectedvendor = vendorInput.value;
    
        // Validate customer selection
        if (!selectedvendor) {
            alert("Please select a valid vendor before adding items.");
            document.getElementById('vendor-name-input').focus();
            return;
        }

            
        
        // Get product code from input
        var productCode = document.getElementById('product-input').value;
        
        // Locate the corresponding option in the datalist to retrieve the product ID and name
        var selectedOption = document.querySelector(`#product_name option[value="${productCode}"]`);
        if (!selectedOption) {
            alert("Product not found. Please select a valid product.");
            return;
        }


        // Get UOM code from input
        var uomCode = document.getElementById('uom-name-input').value;

        // Locate the corresponding option in the datalist to retrieve the UOM ID and name
        var selectedUOMOption = document.querySelector(`#UOMList option[value="${uomCode}"]`);
        if (!selectedUOMOption) {
            alert("UOM not found. Please select a valid UOM.");
            return;
        }
        
        
        var productId = selectedOption.getAttribute('data-id');
        var productName = selectedOption.textContent;
        
        // Get quantity, rate, discount values
        var qty = parseFloat(document.getElementById('qty_id').value) || 0;
        var rate = parseFloat(document.getElementById('price_id').value);
        var discountType = document.getElementById('discount_type').value; // Discount type (flat or percentage)
        var discountValue = parseFloat(document.getElementById('discount_value').value);
        var inwardWarehouse = document.getElementById('warehouse-name-input').value;
        
        // Get the selected UOM value and UOM id (from data-id)
        var uomInput = document.getElementById('uom-name-input');
        var uomAbbreviation = uomInput.value;
        var selectedUomOption = document.querySelector(`#UOMList option[value="${uomAbbreviation}"]`);
        var uomId = selectedUomOption ? selectedUomOption.getAttribute('data-id') : null;
    
        // Validate price
        if (isNaN(rate) || rate <= 0) {
            alert("Please enter a valid price.");
            document.getElementById('price_id').focus(); // Focus the price field for the user to correct it
            return;
        }
    
        // Calculate the net rate based on the discount type
        var netRate = rate;
    
        // Apply discount if any
        if (discountType === 'percentage' && discountValue > 0) {
            netRate = rate - (rate * discountValue / 100); // Percentage discount
        } else if (discountType === 'flat' && discountValue > 0) {
            netRate = rate - discountValue; // Flat discount
        }
    
        // Ensure netRate is not negative
        netRate = Math.max(netRate, 0);
    
        // Create a new row in the order items table
        var table = document.getElementById('purchaseItemsTable').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow();
        
        // Store productCode, productId, and UOM id as hidden attributes in the row for reference
        newRow.setAttribute('data-product-code', productCode);
        newRow.setAttribute('data-product-id', productId);
        newRow.setAttribute('data-uom-id', uomId);
        newRow.setAttribute('data-warehouse-id', inwardWarehouse);
        
        
        // Create the table cells
        var cell1 = newRow.insertCell(0); // Product Name
        var cell2 = newRow.insertCell(1); // Quantity (editable)
        var cell3 = newRow.insertCell(2); // UOM (after quantity)
        var cell4 = newRow.insertCell(3); // Rate
        var cell5 = newRow.insertCell(4); // Discount
        var cell6 = newRow.insertCell(5); // Net Rate
        var cell7 = newRow.insertCell(6); // Amount
        var cell8 = newRow.insertCell(7); // Exit Warehouse
        var cell9 = newRow.insertCell(8); // Delete button
        
        // Display product name
        cell1.innerHTML = productName;
        
        // Create editable input for quantity and add it to cell2
        var input = createEditableQuantityCell(qty);
        cell2.appendChild(input);
        
        // Set UOM in the next column
        cell3.innerHTML = uomAbbreviation;
        
        // Set rate
        cell4.innerHTML = rate.toFixed(2);
        
        // Set discount information
        if (discountType === 'percentage' && discountValue > 0) {
            cell5.innerHTML = discountValue.toFixed(2) + '%';
        } else if (discountType === 'flat' && discountValue > 0) {
            cell5.innerHTML = discountValue.toFixed(2);
        } else {
            cell5.innerHTML = '-';
        }
    
        // Set net rate and amount
        cell6.innerHTML = netRate.toFixed(2);
        var amount = (qty * netRate).toFixed(2);  // Amount based on netRate, not rate
        cell7.innerHTML = amount;
        
        // Display exit warehouse status (Yes or No)
        cell8.innerHTML = inwardWarehouse;
        
        // Create delete button
        var deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.innerHTML = "Delete";
        deleteButton.classList.add("btn", "btn-danger", "btn-sm");
        deleteButton.onclick = function() { removeItem(deleteButton); };
        cell9.appendChild(deleteButton);
        
        // Clear the input fields after adding the item
        document.getElementById('product-input').value = '';
        document.getElementById('qty_id').value = '1';
        document.getElementById('price_id').value = '';
        document.getElementById('discount_value').value = '';
        
        // Recalculate totals after adding the item
        //recalculateTotals();
        document.getElementById('product-input').focus();
    }
    
    
    
  // Vendor name validation & get id 
const vendorNameInput = document.getElementById('vendor-name-input');
const vendorIdField = document.getElementById('vendor-id');


vendorNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#vendor-names option[value="${vendorNameInput.value}"]`);
    if (selectedOption) {
        const vendorId = selectedOption.getAttribute('data-id');
        vendorIdField.value = vendorId;
    } else {
        vendorIdField.value = ''; 
    }
});

// Warehouse name validation & get ID
const warehouseNameInput = document.getElementById('warehouse-name-input');
const warehouseIdField = document.getElementById('warehouse-id');

warehouseNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#warehouse-names option[value="${warehouseNameInput.value}"]`);
    if (selectedOption) {
        const warehouseId = selectedOption.getAttribute('data-id');
        warehouseIdField.value = warehouseId;
    } else {
        warehouseIdField.value = ''; 
    }
});


// UOM selection validation and ID assignment
const uomNameInput = document.getElementById('uom-name-input');
const uomIdField = document.getElementById('uom-id');

uomNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#UOMList option[value="${uomNameInput.value}"]`);
    if (selectedOption) {
        const uomId = selectedOption.getAttribute('data-id');
        uomIdField.value = uomId;
    } else {
        uomIdField.value = '';  // Reset if no match
    }
});


// Set the current date and time as the default value
window.onload = function() {
    var dateInput = document.getElementById('purchase-date-input');
    
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); 
    var day = currentDate.getDate().toString().padStart(2, '0');
    var hours = currentDate.getHours().toString().padStart(2, '0');
    var minutes = currentDate.getMinutes().toString().padStart(2, '0');
    var formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
    dateInput.value = formattedDate;
};
