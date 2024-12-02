

let selectedProducts = [];

document.addEventListener('DOMContentLoaded', () => {

  // Fetch the products data
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
    .catch(error => console.error('Error fetching products:', error));

});


document.addEventListener('DOMContentLoaded', function() {

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
  