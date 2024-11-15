<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Overview</title>
    <link rel="stylesheet" href="stylesreport.css">
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/quill/quill.snow.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Scripts -->
    <script src="assets/quill/quill.min.js"></script>
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('fetch_report_view.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#data-table tbody');
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        Object.values(row).forEach(cell => {
                            const td = document.createElement('td');
                            td.textContent = cell;
                            tr.appendChild(td);
                        });

                        // Add eye icon in the "View" column
                        const viewTd = document.createElement('td');
                        const eyeIcon = document.createElement('i');
                        eyeIcon.classList.add('fas', 'fa-eye'); // Font Awesome classes for the eye icon
                        eyeIcon.style.cursor = 'pointer';
                        eyeIcon.addEventListener('click', () => {
                            if (['imprest_safari', 'imprest_expenditure', 'incidents', 'memos', 'retirement'].includes(row.request_type)) {
                                // Fetch data from the specific table
                                fetch(`fetch_details_for_table.php?table=${row.request_type}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const modalTableBody = document.querySelector('#modal-content tbody');
                                        const modalTableHead = document.querySelector('#modal-content thead');
                                        modalTableBody.innerHTML = ''; // Clear previous content
                                        modalTableHead.innerHTML = ''; // Clear previous headers
                                        if (data.rows.length > 0) {
                                            // Create table headers
                                            const headers = data.columns;
                                            const tr = document.createElement('tr');
                                            headers.forEach(header => {
                                                const th = document.createElement('th');
                                                th.textContent = header;
                                                tr.appendChild(th);
                                            });
                                            modalTableHead.appendChild(tr);

                                            // Populate table rows
                                            data.rows.forEach(row => {
                                                const tr = document.createElement('tr');
                                                Object.values(row).forEach(cell => {
                                                    const td = document.createElement('td');
                                                    td.textContent = cell;
                                                    tr.appendChild(td);
                                                });
                                                modalTableBody.appendChild(tr);
                                            });
                                        }
                                        document.querySelector('#myModal').style.display = 'block';
                                    })
                                    .catch(error => console.error('Error fetching table data:', error));
                            } else {
                                // Display only the request_type
                                document.querySelector('#modal-content pre').textContent = row.request_type;
                                document.querySelector('#myModal').style.display = 'block';
                            }
                        });
                        viewTd.appendChild(eyeIcon);
                        tr.appendChild(viewTd);

                        tableBody.appendChild(tr);
                    });
                });
        });

        // Close the modal when the user clicks anywhere outside of the modal content
        window.addEventListener('click', function(event) {
            const modal = document.querySelector('#myModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    </script>
 
</head>

<body>
    <div class="container">
        <div class="header-container">
          
            <h1>Request Overview Summary</h1>
        
        </div>
        <table id="data-table">
            <thead>
                <tr>
                    <th>Request Type</th>
                    <th>Total Requests</th>
                    <th>Pending Requests</th>
                    <th>Approved Requests</th>
                    <th>Declined Requests</th>
                    <th>Retired Requests</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody style="color: #007BFF;">
                <!-- Data will be injected here by JavaScript -->
            </tbody>
        </table>
    </div>
 

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <!--span class="close print-hide">&times;</span-->
            <div id="modal-content">
                <table>
                    <!--a class="print-link print-hide" href="javascript:window.print()" style="text-decoration: none;">Print</a-->
                    <thead>
                        <!-- Table headers will be dynamically injected -->
                    </thead>
                    <tbody>
                        <!-- Table data will be dynamically injected -->
                    </tbody>
                </table>
                <pre></pre>
            </div>
        </div>
    </div>

    <script>
        // Close the modal when the user clicks on <span> (x)
        document.querySelector('.close').addEventListener('click', function() {
            document.querySelector('#myModal').style.display = 'none';
        });
    </script>
</body>
</html>
