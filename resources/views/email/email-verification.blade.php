<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskApp</title>
</head>

<body>

    <div class="container">
        <div class="card card-body" style="margin-left:40px; margin-left:50px;">
            <div align="center">
                <a href="{{url('/')}}"><img src="{{url('/others/logo.png')}}" alt="" style="width:100px"></a>
            </div>
            <h3>E-mail Verification.</h3>
            <p>Hello , <br> <br>
                we have sent this email to you to check if this Email : <a href="#">{{$user->email}}</a>
                you provide is a valid one ; Click on the link below to verify it.

                <a style="font-weight: bold;color:blue" target="_blank"
                href="http://127.0.0.0:8000/check_email/{{$user->remember_token}}">
                Verify my email
                </a>
            </p>
            <br/>
            <p>Your Sincerely.</p>
        </div>

    </div>
</body>

</html>
