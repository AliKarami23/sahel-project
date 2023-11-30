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

        /*.error {*/
        /*    color: red;*/
        /*}*/
    </style>
</head>
<body>
{{--@if(isset($data))--}}
    <h2 class="success">پرداخت با موفقیت انجام شد</h2>
{{--    <p>وضعیت: {{ $data['status'] }}</p>--}}
{{--    <p>شناسه تراکنش: {{ $data['id'] }}</p>--}}
{{--    <p>کد رهگیری: {{ $data['track_id'] }}</p>--}}
{{--    <p>شماره فاکتور: {{ $data['order_id'] }}</p>--}}
{{--    <p>مقدار پرداخت: {{ $data['amount'] }}</p>--}}
{{--    <p>شماره کارت: {{ $data['card_no'] }}</p>--}}
{{--    <p>تاریخ پرداخت: {{ $data['date'] }}</p>--}}
{{--@elseif(isset($error))--}}
{{--    <h2 class="error">خطا در پرداخت</h2>--}}
{{--    <p>کد خطا: {{ $error['error_code'] }}</p>--}}
{{--    <p>پیام خطا: {{ $error['error_message'] }}</p>--}}
{{--@else--}}
{{--    <h2 class="error">پاسخ نامعتبر</h2>--}}
{{--@endif--}}
</body>
</html>
