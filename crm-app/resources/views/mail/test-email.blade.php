<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Payment Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        .button {
            background-color: #28a745;
            color: white !important;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
            font-weight: bold;
        }
        .message {
            white-space: pre-line;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Invoice Payment Request</h1>

        <div class="message">
            {!! nl2br(e($messageText)) !!}
        </div>

        <a href="{{ route('invoice.pay', ['id' => $invoice_id]) }}" class="button">
            Pay Now
        </a>

        <p style="margin-top: 30px; color: #666;">
            Best regards,<br>
            {{ config('mail.from.name') }}
        </p>
    </div>
</body>
</html>
