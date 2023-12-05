<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket</title>
</head>
<body>
<h1>Ticket Information</h1>
<p>Card Number: {{ $cardNumber }}</p>
<p>Tickets Sold (Man): {{ $ticketsSoldMan }}</p>
<p>Tickets Sold (Woman): {{ $ticketsSoldWoman }}</p>
<p>Sans Information:</p>
<ul>
    <li>Start: {{ $sansStart }}</li>
    <li>End: {{ $sansEnd }}</li>
    <li>Date: {{ $sansDate }}</li>
</ul>
</body>
</html>
