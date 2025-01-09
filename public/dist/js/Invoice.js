function setTextContentById(id, value) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = value;
    }
}



function getOrderForEdit() {
    
    var saleId = document.querySelector('input[name="custom_order_id"]').value;

    if (saleId) {
        fetch(`/get-invoice/${saleId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error); 
                } else {
                    
                    populateOrderDetails(data);
                    recalculateTotalsSale();
                    
                    document.getElementById('submitOrder').style.display = 'none'; 
                    document.getElementById('updateOrder').style.display = 'inline-block';
                    document.getElementById('cancelOrder').style.display = 'inline-block';
                
                    
                }
            })
            .catch(error => {
                console.error("Error fetching order data:", error);
                alert("There was an error retrieving the order data.");
            });
    } else {
        alert("Sale ID is missing or invalid.");
    }
}


function populateOrderDetails(data) {

    const orderDateInput = document.querySelector('input[name="order_date"]');
    const customerNameInput = document.querySelector('input[name="customer-name"]');
    const salespersonNameInput = document.querySelector('input[name="salesperson-name"]');

    const orderStatusId = document.querySelector('input[name="status_id"]');
    const orderStatusInput = document.querySelector('input[name="order_status"]');

    


    const customerIdInput = document.querySelector('input[name="customer_id"]');
    const salespersonIdInput = document.querySelector('input[name="salesperson_id"]');
    const paymentStatusInput = document.querySelector('input[name="payment_status"]');
    const paidAmountInput = document.querySelector('input[name="paid_amount"]');
    
    const remainingAmountInput = document.querySelector('input[name="remaining_amount"]');
    const orderDiscountInput = document.querySelector('input[name="order_discount"]');
    const discountTypeFlat = document.querySelector('input[name="order_discount_type"][value="flat"]');
    const discountTypePercentage = document.querySelector('input[name="order_discount_type"][value="percentage"]');
    const otherChargesInput = document.querySelector('input[name="other_charges"]');  
    const grossAmountInput = document.querySelector('input[name="gross_amount"]');  
    const netTotalInput = document.querySelector('input[name="net_amount"]'); 
    const balanceInput = document.querySelector('input[name="balance"]');
    const taxRateSelect = document.querySelector('select[name="tax_rate"]');
    
    // Populate other charges field
    if (otherChargesInput) {
        const otherCharges = data.otherCharges || 0;
        otherChargesInput.value = otherCharges;
    }

    if (grossAmountInput) {
        // Temporarily enable the input, set the value, and disable it again
        grossAmountInput.disabled = false;
        grossAmountInput.value = data.grossAmount || 0;
        grossAmountInput.disabled = true;
    }

    if (netTotalInput) {
      
         netTotalInput.disabled = false;
         netTotalInput.value = data.netTotal.toFixed(2); // Set the value with two decimal points
         netTotalInput.disabled = true;
    }
       
    if (balanceInput) {
        balanceInput.disabled = false; 
        balanceInput.value = data.remainingAmount.toFixed(2);  
        balanceInput.disabled = true;  
    }

    if (orderDateInput && customerNameInput && salespersonNameInput && orderStatusInput) {
        orderDateInput.value = data.order.order_date || '';
        customerNameInput.value = data.order.customer ? data.order.customer.name : '';
        salespersonNameInput.value = data.order.sales_manager_name || '';
        orderStatusId.value = data.order.status || '';
        orderStatusInput.value = data.order.status_name || '';
        

        if (customerIdInput) customerIdInput.value = data.order.customer ? data.order.customer.id : '';
        if (salespersonIdInput) salespersonIdInput.value = data.order.sale_manager_id;
        if (paymentStatusInput) paymentStatusInput.value = data.order.payment_status || '';
        if (paidAmountInput) paidAmountInput.value = data.order.paid || '';
        
        if (remainingAmountInput) remainingAmountInput.value = data.order.remainingAmount || '';

            
        
            const discountAmount = data.order.discount_amount || 0;
            const discountType = data.order.discount_type || '-';  // Default to flat
            console.log("discount",discountAmount);
            // Populate order discount value
            orderDiscountInput.value = discountAmount;

                if (discountType === '-') {
                    orderDiscountInput.value = discountAmount;
                    discountTypeFlat.checked = true;  
                    discountTypePercentage.checked = false;  
                } else if (discountType === '%') {
                    orderDiscountInput.value = discountAmount; 
                    
                    discountTypePercentage.checked = true;  
                    discountTypeFlat.checked = false;  
                }

                if (taxRateSelect) {
                    const taxRate = parseFloat(data.order.tax_rate).toFixed(2); 
                    let optionFound = false; 
                    Array.from(taxRateSelect.options).forEach(option => {
                        
                        if (option.value === taxRate) {
                            option.selected = true;
                            optionFound = true; 
                        }
                    });
                  
                    if (!optionFound) {
                        taxRateSelect.selectedIndex = 0; 
                    }
                } 
        
                var table = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
                
                

                data.orderItems.forEach(item => {
                    console.log("list",item);
                    var newRow = table.insertRow();   
                    // Set custom attributes on the new row
                    newRow.setAttribute('data-product-code', item.product_code);
                    newRow.setAttribute('data-product-id', item.product_id);
                    newRow.setAttribute('data-uom-id', item.uom_id);
                    newRow.setAttribute('data-warehouse-id', item.exit_warehouse);
                    
                    
                
                    const cell1 = newRow.insertCell(0);
                    const cell2 = newRow.insertCell(1);
                    const cell3 = newRow.insertCell(2);
                    const cell4 = newRow.insertCell(3);
                    const cell5 = newRow.insertCell(4);
                    const cell6 = newRow.insertCell(5);
                    const cell7 = newRow.insertCell(6);
                    const cell8 = newRow.insertCell(7);
                    const cell9 = newRow.insertCell(8);
                
                    cell1.innerHTML = item.product_name;
                
                    const input = createEditableQuantityCell(item.quantity);
                    cell2.appendChild(input);
                
                    cell3.innerHTML = item.uom_name;
                
                    cell4.innerHTML = parseFloat(item.unit_price).toFixed(2);
                
                    if (item.discount_amount.includes('%')) {
                        cell5.innerHTML = item.discount_amount;
                    } else {
                        cell5.innerHTML = item.discount_amount;
                    }
                
                    cell6.innerHTML = parseFloat(item.net_rate).toFixed(2);
                    const amount = (item.quantity * item.net_rate).toFixed(2);
                    cell7.innerHTML = amount;
                
                    cell8.innerHTML = item.warehouse_name ;
                
                    const deleteButton = document.createElement("button");
                    deleteButton.type = "button";
                    deleteButton.innerHTML = "Delete";
                    deleteButton.classList.add("btn", "btn-danger", "btn-sm");
                    deleteButton.onclick = function () { removeItem(deleteButton); };
                    cell9.appendChild(deleteButton);
                });
      

    } else {
        console.error('Some required input elements are missing in the DOM.');
    }
}





function getInvoiceDetails(customOrderId = null) {
    let saleId = customOrderId;

    $('#loader').show(); 
    if (!saleId) {
        saleId = document.querySelector('input[name="custom_order_id"]').value;
        $('#loader').hide(); 
    }



    if (saleId) {
        fetch(`/get-invoice/${saleId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    $('#loader').hide(); 
                    console.log(data);
                    // Extract values and set defaults
                    const currencySymbol = data.currencySymbol || '$';
                    const companyName = data.companyName || '';
                    const companyAddress = data.companyAddress || '';
                    const companyPhone = data.companyPhone || '';
                    const companyEmail = data.companyEmail || '';
                     
                    const grossAmount = (data.grossAmount && !isNaN(data.grossAmount)) ? data.grossAmount.toFixed(2) : '0.00';
                    const otherCharges = (data.otherCharges && !isNaN(data.otherCharges)) ? data.otherCharges.toFixed(2) : '0.00';
                    const netTotal = (data.netTotal && !isNaN(data.netTotal)) ? data.netTotal.toFixed(2) : '0.00';
                    const paidAmount = (data.paidAmount && !isNaN(data.paidAmount)) ? data.paidAmount.toFixed(2) : '0.00';
                    const AmountDue = (data.remainingAmount && !isNaN(data.remainingAmount)) ? data.remainingAmount.toFixed(2) : '0.00';
                    
                    const taxAmount = data.taxAmount || ''; 
                    const taxRate = (data.taxRate && !isNaN(data.taxRate)) ? data.taxRate : '0.00';
                    

                    
                    const status = data.order.status || 'N/A';
                    const saleNote = data.order.sale_note || 'N/A';
                    const orderDate = data.order.order_date || 'N/A';
                    const amountDueDate = data.amountDueDate || 'N/A';
                    const orderId = data.order.custom_order_id || 'N/A';
                    const customerName = data.order.customer.name || 'N/A';
                    const customerAddress = data.order.customer.address || 'N/A';
                    const customerPhone = data.order.customer.phone || 'N/A';
                    const customerEmail = data.order.customer.email || 'N/A';
					const discount_amount = data.order.discount_amount || 'N/A';

                    // Populate invoice details
                    setTextContentById('invoiceDate', orderDate);
                    setTextContentById('invoiceToName', customerName);
                    setTextContentById('invoiceToAddress', customerAddress);
                    setTextContentById('invoiceToPhone', customerPhone);
                    setTextContentById('invoiceToEmail', customerEmail);

                    setTextContentById('companyName', companyName);
                    setTextContentById('companyAddress', companyAddress);
                    setTextContentById('companyPhone', companyPhone);
                    setTextContentById('companyEmail', companyEmail);
                    
                    setTextContentById('saleNote', saleNote);
                    setTextContentById('invoiceNumber', orderId);
                    setTextContentById('orderId', orderId);
					
                    
                    setTextContentById('AmountDue', `${currencySymbol} ${AmountDue}`);  
                    setTextContentById('AmountDueTop', `${currencySymbol} ${AmountDue}`); 
                    
                    // Update items (ensure the orderItems array exists and contains data)
                    const invoiceItems = data.orderItems.map(item => `
                        <tr>
                            <td>${item.product_name}</td>
                            <td>${item.quantity} ${item.uom_name}</td>
                            
                            <td>${currencySymbol} ${item.unit_price}</td> 
                            <td> 
                                ${item.discount_type === '%' ? `${item.discount_amount} %` : `${currencySymbol} ${item.discount_amount}`}
                            </td>

                            <td>${currencySymbol} ${item.net_rate}</td> 
                            <td>${currencySymbol} ${item.amount.toFixed(2)}</td> 
                   
                        </tr>
                    `).join('');
                    document.getElementById('invoiceItems').innerHTML = invoiceItems;

                    // Update amounts
                    setTextContentById('subtotalAmount', `${currencySymbol} ${grossAmount}`);
                    setTextContentById('otherCharges', `${currencySymbol} ${otherCharges}`);
                    setTextContentById('totalAmount', `${currencySymbol} ${netTotal}`);
                    setTextContentById('paidAmount', `${currencySymbol} ${paidAmount}`);
                    if (discount_amount > 0) {
                        setTextContentById('discountAmount', `${currencySymbol} ${discount_amount}`);
                    } 
                    
                    if (taxAmount > 0) {
                        setTextContentById('taxRate', `${currencySymbol} ${taxAmount} (${taxRate}%)`);
                    } 
                    

                    // Show the modal
                    $('#invoiceModal').modal('show');
                    $('#loader').hide(); 
                }
            })
            .catch(error => {
                $('#loader').hide(); 
                console.error('Error fetching invoice:', error);
                alert('An error occurred while fetching the invoice.');
            });
    } else {
        $('#loader').hide(); 
        alert('Please enter a Sale ID');
        
    }
}

function printInvoice() {
    // Get the modal content


    var printContents = document.getElementById('invoiceContent').innerHTML;

    // Create a temporary iframe for printing
    var iframe = document.createElement('iframe');
    iframe.style.position = 'absolute';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);

    var doc = iframe.contentWindow.document;

    // Add modal-specific styles
    var style = `
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .invoice {
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .page-header {
            font-size: 24px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        .logo-img-invoice img {
            max-height: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-muted {
            color: #6c757d;
        }
        .well {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
        }
    `;

    // Write the content and styles to the iframe
    doc.open();
    doc.write(`
        <html>
        <head>
            <title>Print Invoice</title>
            <style>${style}</style>
        </head>
        <body>
            ${printContents}
        </body>
        </html>
    `);
    doc.close();

    // Wait until the iframe content is loaded
    iframe.contentWindow.focus();
    iframe.contentWindow.print();

    // Remove the iframe after printing
    document.body.removeChild(iframe);
}

async function getSaleDataForSharing(customSaleId = null, action = 'print') {
    $('#loader').show(); 
    let saleId = customSaleId;

    if (!saleId) {
        saleId = document.querySelector('input[name="custom_sale_id"]').value;
        $('#loader').hide(); 
    }

    if (saleId) {
        fetch(`/get-invoice/${saleId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    $('#loader').hide(); 
                } else {
                    console.log(data);

                    const currencySymbol = data.currencySymbol ? `${data.currencySymbol} ` : ''; 
                    const companyName = data.companyName || '';
                    const companyAddress = data.companyAddress || '';
                    const companyPhone = data.companyPhone || '';
                    const companyEmail = data.companyEmail || '';  

                    const grossAmount = data.grossAmount ? `${currencySymbol}${parseFloat(data.grossAmount).toFixed(2)}` : `${currencySymbol}0.00`;
                    const otherCharges = data.otherCharges ? `${currencySymbol}${parseFloat(data.otherCharges).toFixed(2)}` : `${currencySymbol}0.00`;
                    const netTotal = data.netTotal ? `${currencySymbol}${parseFloat(data.netTotal).toFixed(2)}` : `${currencySymbol}0.00`;
                    const paidAmount = data.paidAmount ? `${currencySymbol}${parseFloat(data.paidAmount).toFixed(2)}` : `${currencySymbol}0.00`;
                    const amountDue = data.remainingAmount ? `${currencySymbol}${parseFloat(data.remainingAmount).toFixed(2)}` : `${currencySymbol}0.00`;

                    const order = data.order || {};
                    const customer = order.customer || {};

                    const saleDate = order.order_date || 'N/A';
                    const saleOrderId = order.custom_order_id || 'N/A';
                    const customerName = customer.name || 'N/A';
                    const customerAddress = customer.address || 'N/A';
                    const customerPhone = customer.phone || 'N/A';
                    const customerEmail = customer.email || 'N/A';
                    const saleNote = order.sale_note || 'N/A';
                    const discountAmount = order.discount_amount ? `${currencySymbol}${parseFloat(order.discount_amount).toFixed(2)}` : `${currencySymbol}0.00`;

                    setTextContentById('invoiceDate-watsapp', saleDate);
                    setTextContentById('customerName-watsapp', customerName);
                    setTextContentById('customerAddress-watsapp', customerAddress);
                    setTextContentById('customerPhone-watsapp', customerPhone);
                    setTextContentById('customerEmail-watsapp', customerEmail);

                    setTextContentById('companyName-watsapp', companyName);
                    setTextContentById('companyAddress-watsapp', companyAddress);
                    setTextContentById('companyPhone-watsapp', companyPhone);
                    setTextContentById('companyEmail-watsapp', companyEmail);
                    
                    setTextContentById('saleNote-watsapp', saleNote);
                    setTextContentById('saleOrderId-watsapp', saleOrderId);
                    setTextContentById('discountAmount-watsapp', discountAmount);
                    setTextContentById('amountDue-watsapp', amountDue);

                    const saleItems = (data.orderItems || []).map(item => `
                        <tr>
                            <td>${item.product_name || 'N/A'}</td>
                            <td>${item.quantity || '0'}</td>
                            <td>${currencySymbol}${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                            <td>${currencySymbol}${parseFloat(item.discount_amount || 0).toFixed(2)}</td>
                            <td>${currencySymbol}${parseFloat(item.net_rate || 0).toFixed(2)}</td>
                            <td>${currencySymbol}${parseFloat(item.amount || 0).toFixed(2)}</td>
                        </tr>
                    `).join('');
                    document.getElementById('saleInvoiceItems-watsapp').innerHTML = saleItems;

                    setTextContentById('subtotalAmount-watsapp', grossAmount);
                    setTextContentById('otherCharges-watsapp', otherCharges);
                    setTextContentById('paidAmount-watsapp', paidAmount);

                    if (action === 'print') {
                        $('#loader').hide(); 
                        printModalContent('.canva-section-watsapp');
                    } else if (action === 'share') {
                        $('#loader').hide(); 
                        shareSectionAsImageSaleOrder('.canva-section-watsapp', {
                            fileName: 'invoice.png',
                            title: 'Invoice',
                            text: 'Check out this invoice!',
                        });
                    }
                }
            })
            .catch(error => {
                $('#loader').hide(); 
                console.error('Error fetching sale invoice:', error.message || error);
                alert('An error occurred while fetching the sale invoice.');
            });
    } else {
        $('#loader').hide(); 
        alert('Please enter a Sale ID');
    }
}




async function shareSectionAsImageSaleOrder(sectionSelector, options = {}) {
    const section = document.querySelector(sectionSelector);

    if (!section) {
        console.error(`Section with selector "${sectionSelector}" not found.`);
        return;
    }

    // Show the section before capturing it
    section.style.display = 'block';

    try {
        const canvas = await html2canvas(section, { useCORS: true });
        const image = canvas.toDataURL("image/png");

        // Check the canvas data
        console.log("Canvas image generated:", image);

        const blob = await fetch(image).then((res) => res.blob());

        // Check the blob size
        console.log(`Blob size: ${blob.size} bytes`);

        if (blob.size === 0) {
            alert("Failed to generate a valid image. Please check the section content.");
            return;
        }

        const file = new File([blob], options.fileName || "document.png", {
            type: "image/png",
        });

        if (navigator.canShare && navigator.canShare({ files: [file] })) {
            await navigator.share({
                files: [file],
                title: options.title || "Shared Content",
                text: options.text || "Check out this!",
            });

            console.log("Shared successfully!");
        } else {
            alert("Your browser does not support image sharing! Downloading the image instead.");
            const link = document.createElement("a");
            link.href = image;
            link.download = options.fileName || "document.png";
            link.click();
        }
    } catch (error) {
        console.error("Error during sharing:", error);
        alert(`Sharing failed: ${error.message}`);
    } finally {
        
        section.style.display = 'none';
    }
}



async function printModalContent(modalSelector, customPurchaseId = null) {
    // Get the section content based on the provided selector
    const section = document.querySelector(modalSelector);

    // Check if the section exists
    if (!section) {
        console.error(`Section with selector "${modalSelector}" not found.`);
        return;
    }

    // Show the section before capturing it
    section.style.display = 'block';

    try {
        // Generate the canvas from the modal content (using section instead of modalContent)
        const canvas = await html2canvas(section, {
            useCORS: true,
            scale: 1, // Adjust for better resolution (increase if needed for higher quality)
            x: 0, // Optional: Adjust the x position to fit the content
            y: 0, // Optional: Adjust the y position
            width: section.offsetWidth, // Ensure the width matches the section's width
            height: section.offsetHeight, // Ensure the height matches the section's height
            scrollX: 0, // Disable horizontal scroll when capturing
            scrollY: 0, // Disable vertical scroll when capturing
            backgroundColor: null, // Transparent background (useful if modal has custom background)
        });

        // Convert the canvas to an image
        const image = canvas.toDataURL("image/png");

        // Open a new window for printing
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print Invoice</title>');
        
        // Add CSS styles for the print layout
        printWindow.document.write('<style>');
        printWindow.document.write(`
            body {
                margin: 0;
                padding: 0;
                text-align: center;
            }
            img {
                width: 100%; /* Ensure the image takes full width */
                height: auto; /* Maintain aspect ratio */
                max-width: 100%; /* Prevent image from being stretched */
            }
        `);
        printWindow.document.write('</style>');
        
        printWindow.document.write('</head><body>');
        printWindow.document.write(`<img src="${image}" />`);
        printWindow.document.write('</body></html>');
        
        // Close the document to trigger the print window
        printWindow.document.close();

        // Wait for the new window to load before triggering the print
        printWindow.onload = () => {
            printWindow.print();
            printWindow.close();
        };

        // Hide the section after capturing it
        section.style.display = 'none';

    } catch (error) {
        console.error("Error generating image for printing:", error);
        alert("Error generating image for printing.");
    }
}
