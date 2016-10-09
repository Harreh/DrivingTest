<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <title>Driving test alert</title>
</head>
<body>
<div class="container">
    <div class="col-md-12">
        <h1>Hello!</h1>

        <p>Looks like there's an earlier booking availble.</p>
        <p>The date is <strong>{{ $booking->date->format('l j F Y g:ia') }}</strong>.</p>

        <a class="btn btn-success" href="https://www.gov.uk/change-driving-test" role="button">Book</a>
    </div>
</div>
</body>
</html>
