<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket</title>
</head>
<body>
@foreach ($orderDetails->reserves as $reservation)
    <h1>Ticket Information</h1>
    <p>Card Number: {{ $uniqueCardNumber }}</p>
    <p>Tickets Sold (Man): {{ $reservation->tickets_sold_Man }}</p>
    <p>Tickets Sold (Woman): {{ $reservation->tickets_sold_Woman }}</p>
    <p>Sans Information:</p>
    <ul>
        <li>Start: {{ $reservation->sans->start }}</li>
        <li>End: {{ $reservation->sans->end }}</li>
        <li>Date: {{ $reservation->sans->date }}</li>
    </ul>
    <hr>
@endforeach
</body>
</html>
