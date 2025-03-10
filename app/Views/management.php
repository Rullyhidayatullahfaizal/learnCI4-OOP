<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script> 
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        /* Modal Style */
        .modal {
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px 50px 20px 50px;
            width: 40%;
            border-radius: 10px;
            position: relative;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }

        .modal input {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
        }

        .modal button {
            margin-top: 10px;
            padding: 12px 35px 12px 35px;
            width: 30%;
            background-color: rgb(45, 91, 92);
            color: white;
            border: none;
            cursor: pointer;
                       
        }

        .confirm-delete {
            background-color: red;
            color: white;
        }

        .cancel {
            background-color: gray;
            color: white;
        }


        .modal button:hover {
            background-color: darkblue;
        }

        #exportBtn {
    position: fixed;
    bottom: 20px;
    left: 20px;
    padding: 12px 20px;
    background-color: green;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
    transition: 0.3s;
}

#exportBtn:hover {
    background-color: darkgreen;
}

        
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Customer List<small> Detail Information</small></h2>
    </div>

  <ul class="responsive-table">
    <li class="table-header">
        <div class="col col-1">Name</div>
        <div class="col col-2">Rut</div>
        <div class="col col-3">Email</div>
        <div class="col col-4">Addres</div>
        <div class="col col-5">Phone</div>
        <div class="col col-6">Actions</div>
    </li>
    <div id="customerList"></div> 
  </ul>
</div>

<button id="exportBtn">
    Export to Excel <i class="fa-solid fa-file-excel"></i>
</button>

<!-- MODAL FORM -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModalUpdate()">&times;</span>
        <h3>Update Customer</h3>
        <input type="hidden" id="customerId">
        <label>Name:</label>
        <input type="text" id="customerName">
        <label>Rut:</label>
        <input type="text" id="customerRut">
        <label>Email:</label>
        <input type="email" id="customerEmail">
        <label>Address:</label>
        <input type="text" id="customerAddress">
        <label>Phone:</label>
        <input type="text" id="customerPhone">
        <button onclick="saveCustomer()" >Save Changes</button>
    </div>
</div>


<!-- MODAL KONFIRMASI DELETE -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteModal')">&times;</span>
        <h3>Are you sure?</h3>
        <p>Do you really want to delete this customer?</p>
        <input type="hidden" id="deleteCustomerId">
        <button class="confirm-delete" onclick="confirmDelete()">Yes, Delete</button>
        <button class="cancel" onclick="closeModal('deleteModal')">Cancel</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async function () {
    const customerList = document.getElementById("customerList");

    // get token & role from LocalStorage
    const token = localStorage.getItem("token");
    const role = localStorage.getItem("role"); 

    if (!token) {
        alert("Silakan login terlebih dahulu!");
        window.location.href = "/login";
        return;
    }

    try {
        let response = await fetch("http://localhost:8080/api/customers", {
            method: "GET",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            }
        });

        let result = await response.json();

        if (response.ok && Array.isArray(result) && result.length > 0) {
            let customers = result[0].data; 
            customerList.innerHTML = ""; 

            customers.forEach(customer => {
                let isAgent = role === "agent" ? "" : "disabled"; 
                
                let row = `
                    <li class="table-row">
                        <div class="col col-1" data-label="Name">${customer.name}</div>
                        <div class="col col-2" data-label="Name">${customer.rut}</div>
                        <div class="col col-3" data-label="Email">${customer.email}</div>
                        <div class="col col-4" data-label="Email">${customer.address}</div>
                        <div class="col col-5" data-label="Phone">${customer.phone}</div>
                        <div class="col col-6" data-label="Actions">
                            <button class="update-btn ${isAgent}" onclick="openModal(${customer.id}, '${customer.name}', '${customer.rut}', '${customer.email}', '${customer.address}', '${customer.phone}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="delete-btn ${isAgent}" onclick="openDeleteModal(${customer.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            
                        </div>
                    </li>
                `;
                customerList.innerHTML += row;
            });
        } else {
            customerList.innerHTML = `<p style="color: red;">Gagal mengambil data pelanggan!</p>`;
        }
    } catch (error) {
        console.error("Error:", error);
        customerList.innerHTML = `<p style="color: red;">Terjadi kesalahan saat mengambil data pelanggan.</p>`;
    }
});

// open modal & input data customer
function openModal(id, name, rut, email, address, phone) {
    document.getElementById("customerId").value = id;
    document.getElementById("customerName").value = name;
    document.getElementById("customerRut").value = rut;
    document.getElementById("customerEmail").value = email;
    document.getElementById("customerAddress").value = address;
    document.getElementById("customerPhone").value = phone;

    document.getElementById("updateModal").style.display = "block";
}

// modal delete
function openDeleteModal(customerId) {
    document.getElementById("deleteCustomerId").value = customerId;
    document.getElementById("deleteModal").style.display = "block";
}

// close modal
function closeModalUpdate() {
    document.getElementById("updateModal").style.display = "none";
}

// close modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// save customer
function saveCustomer() {
    const id = document.getElementById("customerId").value;
    const name = document.getElementById("customerName").value;
    const rut = document.getElementById("customerRut").value;
    const email = document.getElementById("customerEmail").value;
    const address = document.getElementById("customerAddress").value;
    const phone = document.getElementById("customerPhone").value;

    fetch(`http://localhost:8080/api/customers/${id}`, {
        method: "PUT",
        headers: {
            "Authorization": `Bearer ${localStorage.getItem("token")}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ name, rut, email, address, phone })
    })
    .then(response => response.json())
    .then(data => {
        alert("Customer berhasil diperbarui!");
        closeModal();
        location.reload(); // Refresh PAGE 
    })
    .catch(error => console.error("Error:", error));
}

// DELETE customer
function confirmDelete() {
    const customerId = document.getElementById("deleteCustomerId").value;

    fetch(`http://localhost:8080/api/customers/${customerId}`, {
        method: "DELETE",
        headers: {
            "Authorization": `Bearer ${localStorage.getItem("token")}`,
            "Content-Type": "application/json"
        }
    })
    .then(() => {
        closeModal('deleteModal');
        location.reload();
    })
    .catch(error => console.error("Error:", error));
}

// ekspor data to spreedsheet
document.getElementById("exportBtn").addEventListener("click", function () {
    const tableData = [];
    const headers = ["Name", "Rut", "Email", "Address", "Phone"];
    tableData.push(headers);

    document.querySelectorAll(".table-row").forEach(row => {
        let rowData = [];
        row.querySelectorAll(".col").forEach((col) => {
            rowData.push(col.innerText.trim());
        });
        tableData.push(rowData);
    });

    const worksheet = XLSX.utils.aoa_to_sheet(tableData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Customers");

    XLSX.writeFile(workbook, "Customer_List.xlsx");
});
</script>

</body>
</html>

<style>
    body {
  font-family: 'lato', sans-serif;
  background-color:#294b4d;
}
.container {
  max-width: 1000px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 10px;
  padding-right: 10px;
}

h2 {
  font-size: 36px;
  color: rgb(10, 248, 252) ;
  margin: 40px 0;
  text-align: center;
  small {
    font-size: 0.5em;
  }
}

.responsive-table {
  li {
    border-radius: 3px;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
  }
  .table-header {
    background-color:rgb(98, 158, 162);
    color:rgb(0, 0, 0);
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }
  .table-row {
    background-color: #ffffff;
    box-shadow: 0px 0px 9px 0px rgb(10, 248, 252);
  }
  .col-1 {
    flex-basis: 15%;
  }
  .col-2 {
    flex-basis: 10%;
  }
  .col-3 {
    flex-basis: 35%;
  }
  .col-4 {
    flex-basis: 15%;
  }
  .col-5 {
    flex-basis: 20%;
  }
  .col-6 {
    flex-basis: 10%;
  }

  
  @media all and (max-width: 767px) {
    .table-header {
      display: none;
    }
    .table-row{
      
    }
    li {
      display: block;
    }
    .col {
      
      flex-basis: 100%;
      
    }
    .col {
      display: flex;
      padding: 10px 0;
      &:before {
        color: rgb(10, 248, 252);
        padding-right: 10px;
        content: attr(data-label);
        flex-basis: 50%;
        text-align: right;
      }
    }
  }
}
</style>