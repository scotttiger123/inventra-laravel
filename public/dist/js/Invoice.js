function setTextContentById(id, value) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = value;
    }
}

function getInvoiceDetails() {
    const saleId = document.querySelector('input[name="custom_order_id"]').value;

    if (saleId) {
        fetch(`/get-invoice/${saleId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Extract values and set defaults
                    const grossAmount = (data.grossAmount && !isNaN(data.grossAmount)) ? data.grossAmount.toFixed(2) : '0.00';
                    const otherCharges = (data.otherCharges && !isNaN(data.otherCharges)) ? data.otherCharges.toFixed(2) : '0.00';
                    const netTotal = (data.netTotal && !isNaN(data.netTotal)) ? data.netTotal.toFixed(2) : '0.00';
                    const paidAmount = (data.paidAmount && !isNaN(data.paidAmount)) ? data.paidAmount.toFixed(2) : '0.00';
                    const remainingAmount = (data.remainingAmount && !isNaN(data.remainingAmount)) ? data.remainingAmount.toFixed(2) : '0.00';
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
                    setTextContentById('saleNote', saleNote);
                    setTextContentById('invoiceNumber', orderId);
                    setTextContentById('orderId', orderId);
					setTextContentById('discountAmount', discount_amount);
					
					
                    

                    // Update items (ensure the orderItems array exists and contains data)
                    const invoiceItems = data.orderItems.map(item => `
                        <tr>
                            <td>${item.product_name}</td>
                            <td>${item.quantity}</td>
                            <td>${item.uom_name}</td>
                            <td>${item.unit_price}</td>
                            <td>
                                ${item.discount_amount}
                            </td>
                            <td>${item.net_rate}</td>
                            <td>$${item.amount.toFixed(2)}</td>
                        </tr>
                    `).join('');
                    document.getElementById('invoiceItems').innerHTML = invoiceItems;

                    // Update amounts
                    setTextContentById('subtotalAmount', `$${grossAmount}`);
                    setTextContentById('otherCharges', `$${otherCharges}`);
                    setTextContentById('totalAmount', `$${netTotal}`);
                    setTextContentById('paidAmount', `$${paidAmount}`);
                    setTextContentById('remainingAmount	', `$${remainingAmount}`);

                    // Show the modal
                    $('#invoiceModal').modal('show');
                }
            })
            .catch(error => {
                console.error('Error fetching invoice:', error);
                alert('An error occurred while fetching the invoice.');
            });
    } else {
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
