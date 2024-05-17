<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .qrscn {
            margin: 0 auto;
        }
        #result {
            margin-left: 500px;
        }
    </style>
</head>
<body>
    <h1 class="text-center font-bold text-4xl mb-5">QR Scanner</h1>
    <div id='reader' style='width:600px' class="qrscn"></div>
</body>
    <script src='https://unpkg.com/html5-qrcode' type='text/javascript'></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Code matched = ${decodedText}`, decodedResult);
            alert("ID Number: " + decodedText); 
        }
        function onScanFailure(error) {
            console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {fps:10, qrbox: {width: 250, height:250}},
        false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</html>