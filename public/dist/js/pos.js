window.onload = function() {
    
  document.getElementById('customer-name-input').focus();
};

let selectedProducts = [];

document.addEventListener('DOMContentLoaded', () => {




  document.addEventListener('keydown', (event) => {
    if (event.ctrlKey || event.metaKey) { // Use Ctrl or Cmd for shortcuts
      switch (event.key.toLowerCase()) {
        case 'o': // Ctrl+O to Submit Order
          event.preventDefault(); // Prevent default browser action
          submitPosOrder();
          break;
        case 'f': // Ctrl+F to Open Financial Modal
          event.preventDefault();
          openFinancialModal();
          break;
        case 'l': // Ctrl+L to Show Loader
          event.preventDefault();
          $('#loader').fadeIn();
          break;
        case 'h': // Ctrl+H to Hide Loader
          event.preventDefault();
          $('#loader').fadeOut();
          break;
        case 'r': // Ctrl+R to Reset Form
          event.preventDefault();
          document.getElementById('orderFormPos').reset();
          clearGridAndSelections();
          clearFinancialFields();
          break;
        default:
          break;
      }
    }
  });

  function openFinancialModal() {
    
              var modal = $('#othersModalLabel');
              modal.modal('show');
  }



  $('#loader').fadeIn();
  fetch('/load-products')
    .then(response => response.json())
    .then(data => {
      console.log(data); // Log the product data to check its structure
      const boxesContainer = document.querySelector(".boxes");

      // Function to display products
      function displayProducts(products) {

        boxesContainer.innerHTML = ''; // Clear existing products
        products.forEach((product) => {
          // Create a new box element for each product
          const box = document.createElement("div");
          box.classList.add("box");

          const productName = product.product_name;
          const productPrice = product.price;
          const imageUrl = product.image_path ? `/storage/${product.image_path}` : "../../dist/img/no-product-img.png";

          const stock = product.initial_stock || 0;
          const currency = product.currency_symbol || '$'; // Default to $
          
          box.setAttribute("data-product-id", product.id); // Store product_id in a data attribute
          console.log(product.id);
          box.innerHTML = `
            <div class="b-img">
              <img src="${imageUrl}" alt="${productName}" />
              <div class="text-overlay">${stock} left</div>
            </div>
            <div class="b-con">
              <p>${productName}</p>
            </div>
            <div class="b-price">
              <h4>${currency}${parseFloat(productPrice).toFixed(2)} </h4>
            </div>
          `;

          // Append the box to the container
          boxesContainer.appendChild(box);

          // Event listener for product click
          box.addEventListener("click", () => {
            let quantity = 1;
            const actionsDiv = document.createElement("div");
            actionsDiv.classList.add("actions");
            const productId = box.getAttribute("data-product-id");
            const warehouseId = document.getElementById('warehouse-id').value; 
            document.getElementById('warehouse-name-input').focus();
           
            if (!warehouseId) {
                alert("Please select a warehouse.");
                return; 
            }


            actionsDiv.innerHTML = `
              <button class="decrease">-</button>
              <span class="quantity">${quantity}</span>
              <button class="increase">+</button>
              <button class="delete">Delete</button>
            `;

            // Add the product to the selectedProducts array
            selectedProducts.push({
              product_id: productId,
              name: productName,
              price: productPrice,
              quantity,
              warehouse_id:warehouseId,
            });

            // Handle Decrease Quantity
            const decreaseBtn = actionsDiv.querySelector(".decrease");
            decreaseBtn.addEventListener("click", (e) => {
              e.stopPropagation(); // Prevent box click event
              if (quantity > 1) {
                quantity--;
                actionsDiv.querySelector(".quantity").textContent = quantity;
              }
              updateTotals();
            });

            // Handle Increase Quantity
            const increaseBtn = actionsDiv.querySelector(".increase");
            increaseBtn.addEventListener("click", (e) => {
              e.stopPropagation(); // Prevent box click event
              quantity++;
              actionsDiv.querySelector(".quantity").textContent = quantity;
              updateTotals();
            });

            // Handle Delete Product from the data div
            const deleteBtn = actionsDiv.querySelector(".delete");
            deleteBtn.addEventListener("click", (e) => {
              e.stopPropagation(); // Prevent box click event
              const index = selectedProducts.findIndex(
                (product) =>
                  product.name === productName && product.price === productPrice
              );
              if (index !== -1) {
                selectedProducts.splice(index, 1); // Remove the product from selectedProducts array
              }
              updateDataDiv();
              updateTotals();
            });

            // Update the data div with the product and its action buttons
            updateDataDiv();
            updateTotals();
          });
        });
      }

      // Initially display all products
      displayProducts(data);

      // Category selection logic
      const categoryInput = document.getElementById('category-name-input');
      const categoryIdInput = document.getElementById('category-id');

      // When the user selects a category, filter products by category
      categoryInput.addEventListener('input', () => {
        const selectedCategoryName = categoryInput.value;

        // Find the selected category ID based on the name
        const selectedCategory = Array.from(document.querySelectorAll('#category-names option'))
          .find(option => option.value === selectedCategoryName);

        if (selectedCategory) {
          const selectedCategoryId = selectedCategory.getAttribute('data-id');
          categoryIdInput.value = selectedCategoryId; // Set the category ID hidden input

          // Filter products based on the selected category ID
          const filteredProducts = data.filter(product => product.category_id == selectedCategoryId);
          displayProducts(filteredProducts); // Display filtered products
        } else {
          // If no category selected, show all products
          displayProducts(data);
        }
      });

    })
    .catch(error => {
      console.error('Error fetching products:', error);
    })
    .finally(() => {
      // Hide the loader with fadeOut effect after the data is loaded
      $('#loader').fadeOut(); // Hide loader with fadeOut effect
    });




        

    function updateDataDiv() {
      const dataDiv = document.querySelector(".data");
      dataDiv.innerHTML = ""; 
      selectedProducts.forEach((product, index) => {
        const productDiv = document.createElement("div");
        productDiv.classList.add("product");
        productDiv.innerHTML = `
        <p>${product.name} - $${product.price} ${
          product.quantity > 1 ? "x " + product.quantity : ""
        }</p>
        <div class="actions">
          <button class="decrease">-</button>
          <span class="quantity">${product.quantity}</span>
          <button class="increase">+</button>
          <button class="delete">Delete</button>
        </div>
      `;
        dataDiv.appendChild(productDiv);

        // Handle Decrease Quantity in Data Div
        const decreaseBtn = productDiv.querySelector(".decrease");
        decreaseBtn.addEventListener("click", (e) => {
          e.stopPropagation(); // Prevent box click event
          if (product.quantity > 1) {
            product.quantity--;
            productDiv.querySelector(".quantity").textContent =
              product.quantity;
          }
          updateTotals();
        });

        // Handle Increase Quantity in Data Div
        const increaseBtn = productDiv.querySelector(".increase");
        increaseBtn.addEventListener("click", (e) => {
          e.stopPropagation(); // Prevent box click event
          product.quantity++;
          productDiv.querySelector(".quantity").textContent = product.quantity;
          updateTotals();
        });

        // Handle Delete Product from Data Div
        const deleteBtn = productDiv.querySelector(".delete");
        deleteBtn.addEventListener("click", (e) => {
          e.stopPropagation(); // Prevent box click event
          selectedProducts.splice(index, 1); // Remove the product from selectedProducts array
          updateDataDiv(); // Re-render the data div
          updateTotals(); // Update totals
        });
      });
    }










  const searchInput = document.getElementById('search');
  const productDatalist = document.getElementById('product_name');
  

  // Function to add or update selected products
  function addProduct(product) {
    console.log("product",product);
    if (selectedProducts.some(p => p.product_id === product.product_id)) {
      alert('Product already selected.');
      return;
    }

    selectedProducts.push(product);
    updateDataDiv();
    updateTotals();
  }

  // Fetch product data from backend
  async function fetchProductDetails(productId) {
    try {
      
      const response = await fetch(`/get-product-details/${productId}`);
      
      const data = await response.json();
      
      if (data.success) {
    
        const warehouseId = document.getElementById('warehouse-id').value;
        if (!warehouseId) {
            alert("Please select a warehouse.");
            return; 
        }
         
        const product = {
          product_id: data.product.id,
          name: data.product.product_name,
          price: data.product.price,
          quantity: 1,
          warehouse_id: warehouseId, 
        };
        
        addProduct(product);
      } else {
        alert(data.message);
      }
    } catch (error) {
      console.error('Error fetching product:', error);
    }
  }

  // On selecting from dropdown
  searchInput.addEventListener('input', () => {
    
    const selectedOption = Array.from(productDatalist.options).find(option => option.value === searchInput.value);
    console.log(selectedOption);
    if (selectedOption) {
      const productId = selectedOption.getAttribute('data-id');
      fetchProductDetails(productId);
      searchInput.value = ''; // Clear input
    }
  });
});



function updateTotals() {
  let grossAmount = 0;
  let totalQuantity = 0;
  
    var orderDiscount = parseFloat(document.getElementById('order_discount_id').value) || 0; // Get order discount
    var otherCharges = parseFloat(document.getElementById('other_charges_id').value) || 0; // Get other charges
    document.getElementById('bottom-other-charges-label').textContent = otherCharges;
    var netAmount = 0;
  
    selectedProducts.forEach((product) => {
    grossAmount += product.price * product.quantity;
    totalQuantity += product.quantity;
  });

  const subTotalElement = document.getElementById("sub-total");
  const grandTotalElement = document.getElementById("grand-total");
  const totalQuantityElement = document.getElementById("total-quantity");

  // Display subtotal and grandtotal as $0 instead of 0
  subTotalElement.textContent = `$${grossAmount}`;
  grandTotalElement.textContent = `$${grossAmount}`; // For now, GrandTotal = SubTotal
  totalQuantityElement.textContent = totalQuantity; // Update the total quantity
  
  // Apply discount (flat or percentage)
  var discountType = document.querySelector('input[name="order_discount_type"]:checked').value;
  if (discountType === 'percentage') {
      netAmount = grossAmount - (grossAmount * (orderDiscount / 100));  // Apply discount percentage
  } else {
      netAmount = grossAmount - orderDiscount;  // Apply flat discount
  }
  
  // Get selected tax rate
  var taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
  document.getElementById('bottom-tax-value-label').textContent = taxRate;
  var taxAmount = (netAmount * taxRate) / 100;
  netAmount += taxAmount;


  // Add other charges to the net amount
  netAmount += otherCharges;  // Add other charges

  // Update the total fields
  document.getElementById('gross_amount_id').value = grossAmount.toFixed(2);
  document.getElementById('gross_amount_label').textContent = grossAmount.toFixed(2); 
  
  
  
  document.getElementById('net_amount_id').value = netAmount.toFixed(2);
  document.getElementById('net_amount_label').textContent = netAmount.toFixed(2);

  document.getElementById('paid_amount_label').textContent = (parseFloat(document.getElementById('paid_amount_id').value) || 0).toFixed(2);

  
  document.getElementById('balance_id').value = (netAmount - parseFloat(document.getElementById('paid_amount_id').value || 0)).toFixed(2); 
  document.getElementById('balance_label').textContent = (netAmount - parseFloat(document.getElementById('paid_amount_id').value || 0)).toFixed(2); 
}


// SUBMIT ORDER 

function submitPosOrder() {
  
  event.preventDefault()

  const form = document.getElementById('orderFormPos');
  const formData = new FormData(form);
  
  const customerField = document.getElementById('customer-id-pos');

  if (!customerField || !customerField.value) {
      alert('Customer field is required.');
      return;
  }

  

  
  $('#loader').show();

    var orderData = getPosOrderData();
    if (!orderData || Object.keys(orderData).length === 0) {
        $('#loader').fadeOut();
            return;
    }

  formData.append('orderData', JSON.stringify(orderData));
  formData.forEach((value, key) => {
    console.log(`${key}: ${value}`);
  });

  for (let pair of formData.entries()) {
    console.log(pair[0] + ': ' + pair[1]);
  }

  fetch(form.action, {
      method: 'POST',
      headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
  })
      .then(response => {
          $('#loader').fadeOut();
          

          if (!response.ok) {
              throw new Error(`Server responded with status ${response.status}`);
          }
          return response.json();
      })
      .then(data => {
          if (data.success) {
              console.log(data.success);
              showMessage('success', data.message);
              form.reset();
              clearGridAndSelections();
              clearFinancialFields();
              var modal = $('#othersModalLabel');
              modal.modal('hide');
              $('#loader').fadeOut();

          } else {
              showMessage('error', data.message);
          }
      })
      .catch(error => {
          $('#loader').fadeOut();
          
          showMessage('error', error.message || 'An error occurred while submitting the order.');
      });
}

function showMessage(type, message) {
  var messageDiv;
  
  // Determine the div based on message type
  if (type === 'success') {
      messageDiv = $('#success-message');
  } else {
      messageDiv = $('#error-message');
  }

  // Set the message and show the div
  messageDiv.text(message).fadeIn();

  // Hide the message after 10 seconds with a fade-out effect
  setTimeout(function() {
      messageDiv.fadeOut();
  }, 5000); // 10000 milliseconds = 10 seconds
}  


function getPosOrderData() {
  if (!Array.isArray(selectedProducts) || selectedProducts.length === 0) {
      alert('No products selected for the order.');
      return {};
  }

  return selectedProducts.map(product => ({
      product_id: product.product_id,
      qty: product.quantity,
      rate: product.price,
      warehouse_id: product.warehouse_id
  }));
}




document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('order_discount_id').addEventListener('input', updateTotals);
    document.querySelector('input[name="order_discount_type"]').addEventListener('change', updateTotals);

    document.getElementById('other_charges_id').addEventListener('input', updateTotals);
    document.getElementById('tax_rate').addEventListener('change', updateTotals);
    
    document.getElementById('paid_amount_id').addEventListener('input', updateTotals);
    document.getElementById('clearItems').addEventListener('click', clearGridAndSelections);

  const customerNameInput = document.getElementById('customer-name-input-pos');
  const customerIdField = document.getElementById('customer-id-pos');

  customerNameInput.addEventListener('input', function() {

    


    const selectedOption = document.querySelector(`#customer-names option[value="${customerNameInput.value}"]`);

    if (selectedOption) {
      const customerId = selectedOption.getAttribute('data-id');
      customerIdField.value = customerId;
    } else {
      customerIdField.value = '';
    }
  });




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

  const categoryNameInput = document.getElementById('category-name-input');
  const categoryIdField = document.getElementById('category-id');
  
  categoryNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#category-names option[value="${categoryNameInput.value}"]`);

    if (selectedOption) {
      const categoryId = selectedOption.getAttribute('data-id');
      categoryIdField.value = categoryId;
    } else {
      categoryIdField.value = '';
    }
  });

  const productNameInput = document.getElementById('search');
  const productIdField = document.getElementById('prodcut-id');

  productNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#product_name option[value="${productNameInput.value}"]`);

    if (selectedOption) {
      const productId = selectedOption.getAttribute('data-id');
      productIdField.value = productId;
    } else {
      productIdField.value = '';
    }
  });
});




document.addEventListener('DOMContentLoaded', function() {
if (window.location.pathname.includes('pos')) {
  
  const body = document.body;
  
  if (!body.classList.contains('sidebar-collapse')) {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    if (sidebarToggle) {
      sidebarToggle.click(); 
    }
  }
}
});



function clearGridAndSelections() {
  
  selectedProducts = [];
  const dataDiv = document.querySelector(".data");
  dataDiv.innerHTML = ""; 
  updateTotals();

  // Reset other form inputs if needed
  const customerNameInput = document.getElementById('customer-name-input-pos');
  const customerIdField = document.getElementById('customer-id-pos');
  if (customerNameInput) customerNameInput.value = '';
  if (customerIdField) customerIdField.value = '';

  // Reset any category or product search fields
  const categoryNameInput = document.getElementById('category-name-input');
  const categoryIdField = document.getElementById('category-id');
  const productNameInput = document.getElementById('search');
  if (categoryNameInput) categoryNameInput.value = '';
  if (categoryIdField) categoryIdField.value = '';
  if (productNameInput) productNameInput.value = '';

  
}

function clearFinancialFields() {

    

  document.getElementById('tax_rate').value = "";
  document.getElementById('order_discount_id').value = "";
  document.getElementById('flat_discount_radio').checked = true;
  document.getElementById('percentage_discount_radio').checked = false;
  document.getElementById('gross_amount_id').value = "0";
  document.getElementById('net_amount_id').value = "";
  document.getElementById('other_charges_id').value = "";
  document.getElementById('paid_amount_id').value = "";
  document.getElementById('balance_id').value = "";
  document.getElementById('sale_note').value = "";
  document.getElementById('staff_note').value = "";
  document.getElementById('gross_amount_label').textContent = "0.00";
  document.getElementById('net_amount_label').textContent = "0.00";
  document.getElementById('paid_amount_label').textContent = "0.00";
  document.getElementById('balance_label').textContent = "0.00";
  document.getElementById('bottom-tax-value-label').textContent = "0.00";
  document.getElementById('bottom-other-charges-label').textContent = "0.00";
  
  
}

const toggleBtn = document.getElementById("toggle-btn");
    const chevronUp = document.getElementById("chevron-up");
    const chevronDown = document.getElementById("chevron-down");
    const content = document.getElementById("content");
    const tContainer = document.querySelector(".t-container");

    // Initially set content height
    content.style.height = content.scrollHeight + "px";

    toggleBtn.addEventListener("click", () => {
      if (content.style.height === "0px") {
        // Expand content
        content.style.height = "117px";

        // Move button to the top
        tContainer.style.top = "100px";

        // Toggle arrows
        chevronUp.style.display = "block";
        chevronDown.style.display = "none";
        setTimeout(() => {
          chevronUp.style.opacity = "1";
          chevronDown.style.opacity = "0";
        }, 50);
      } else {
        // Collapse content
        content.style.height = "0px";

        // Move button below the content
        tContainer.style.top = "0px";

        // Toggle arrows
        chevronUp.style.opacity = "0"; // Fade out up arrow
        chevronDown.style.opacity = "1"; // Fade in down arrow

        setTimeout(() => {
          chevronUp.style.display = "none"; // Hide up arrow after fade out
          chevronDown.style.display = "block"; // Show down arrow after fade in
        }, 300);
      }
    });

