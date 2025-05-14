<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
            color: #333;
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
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Invoice Mail</h1>
        <p>{{ $messageText }}</p>

        <a href="{{ $paymentUrl ?? 'https://api.stripe.com' }}" class="button">
            Pay Now
        </a>

        <p style="margin-top: 30px;">Best regards,<br>Weblook CRM Team</p>
    </div>
</body>
</html>
