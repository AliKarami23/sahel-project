<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 100px;
        }

        h2 {
            color: #333;
        }

        p {
            color: #666;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
@if(isset($data))
    <h2 class="success">Payment Successful</h2>
    <p>Transaction ID: {{ $data['id'] }}</p>
    <p>Invoice Number: {{ $data['invoice_number'] }}</p>
    <p>Payment Date: {{ $data['payment_date'] }}</p>
    <p>Status: {{ $data['status'] }}</p>
@elseif(isset($error))
    <h2 class="error">Payment Failed</h2>
    <p>Error: {{ $error }}</p>
@else
    <h2 class="error">Invalid Response</h2>
@endif
</body>
</html>
