<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفریحات دریایی ساحل - بلیط</title>
</head>
<body>
<h1>اطلاعات بلیط</h1>
<p>شماره کارت: {{ $cardNumber }}</p>
<p>لینک کارت: {{ $cardLink }}</p>
<p>تعداد بلیط (مرد): {{ $ticketsSoldMan }}</p>
<p>تعداد بلیط (زن): {{ $ticketsSoldWoman }}</p>
<p>اطلاعات سانس:</p>
<ul>
    <li>شروع: {{ $sansStart }}</li>
    <li>پایان: {{ $sansEnd }}</li>
    <li>تاریخ: {{ $sansDate }}</li>
</ul>
</body>
</html>
