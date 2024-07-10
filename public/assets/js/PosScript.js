
  function imagePreview(event) {
    var file = event.target.files[0];
    const errorMsg = document.getElementById('errorMsg1')
    // Check if a file was selected
    if (file) {
      // Get the file extension (the part after the last dot)
      var fileExtension = file.name.split('.').pop().toLowerCase();

      // Check if the file extension is valid
      if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
        // Check if the file size is less than 800KB (800 * 1024 bytes)
        if (file.size <= 800 * 1024) {
          var image = URL.createObjectURL(file);
          console.log(image);
          var img = document.getElementById('80765imgprofile');
          img.src = image;
          errorMsg.innerHTML = ""
          
        } else {
          errorMsg.innerHTML = "File size exceeds 800KB. Please choose a smaller file."
          // Clear the input value to prevent further processing
          event.target.value = "";
        }
      } else {
        errorMsg.innerHTML = "Invalid file format. Please select a JPG, JPEG, or PNG image."
        // Clear the input value to prevent further processing
        event.target.value = "";
      }
    }
  }

  function hideAlerts() {
    setTimeout(() => {
        var alerts = document.getElementsByClassName('alert show');
        for (let i = 0; i < alerts.length; i++) {
            alerts[i].classList.remove('show');
        }
    }, 5000);
}

hideAlerts();

function printInvoice() {
  // Get the invoice card element
  var invoiceCard = document.querySelector('.invoice-card');

  // Create a new window for printing
  var printWindow = window.open('', '_blank');

  // Write the HTML content directly to the new window
  printWindow.document.write('<html><head><title>Print Invoice</title>');
  printWindow.document.write('<link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap/css/bootstrap.min.css">'); // Link to your CSS file
  printWindow.document.write('<link rel="stylesheet" type="text/css" href="/assets/vendor/simple-datatables/style.css">'); // Link to your CSS file
  printWindow.document.write('<link rel="stylesheet" href="/assets/css/style.css">'); // Link to your CSS file
  printWindow.document.write('<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i">'); // Link to your CSS file
  printWindow.document.write('</head><body>');
  printWindow.document.write(invoiceCard.outerHTML); // Include the HTML content of the invoice card
  printWindow.document.write('</body></html>');

  // Print the new window
  printWindow.print();

  // Close the new window after printing
  printWindow.close();
}
