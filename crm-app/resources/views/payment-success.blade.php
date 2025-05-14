<!-- resources/views/payment-success.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .success-icon {
            color: #28a745;
            font-size: 48px;
            margin-bottom: 1rem;
        }
        .title {
            color: #28a745;
            margin-bottom: 1rem;
        }
        .details {
            margin: 1.5rem 0;
            text-align: left;
        }
        .button {
            background-color: #28a745;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        <h1 class="title">Payment Successful!</h1>
        
        <div class="details">
            <p><strong>Invoice ID:</strong> #{{ $invoice->id }}</p>
            <p><strong>Amount Paid:</strong> ${{ number_format($invoice->total_payment, 2) }}</p>
            <p><strong>Email:</strong> {{ $invoice->email }}</p>
            <p><strong>Status:</strong> <span style="color: #28a745">Paid</span></p>
        </div>

        <a href="{{ route('invoices.index') }}" class="button">Return to Invoices</a>
    </div>
</body>
</html>
