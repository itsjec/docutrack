<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document Tracking</title>
</head>
<body>
<div class="content">
    <h1>Your Document is Already Registered!!</h1>
    <p>Take a note of your tracking number for easy tracking!</p>
    <h2 id="trackingNumber">TRK123456789</h2>
    <button onclick="copyTrackingNumber()">Copy</button>
</div>


<script>
    function copyTrackingNumber() {
        var trackingNumber = document.getElementById("trackingNumber").innerText;
        var tempTextArea = document.createElement("textarea");
        tempTextArea.value = trackingNumber;
        document.body.appendChild(tempTextArea);
        tempTextArea.select();
        tempTextArea.setSelectionRange(0, 99999); 
        document.execCommand("copy");
        document.body.removeChild(tempTextArea);
        alert("Tracking number copied to clipboard!");
    }
</script>
</body>
</html>
