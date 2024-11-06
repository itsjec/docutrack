<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #fff;
            color: #333;
        }

        .container {
            display: flex;
            width: 10in;
            height: 10in;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .image-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .content-section {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }

        p {
            font-size: 1rem;
            margin-bottom: 20px;
            color: #666;
        }

        a {
            display: inline-block;
            padding: 12px 30px;
            background-color: #B557B9;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #55003b;
        }

        img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-section">
            <img src="assets/images/no-access.png" alt="Access Denied">
        </div>
        <div class="content-section">
            <h1>Access Denied</h1>
            <p>You are not authorized to access this page. Please check your login credentials or contact the administrator for access.</p>
            <a href="/">Go to Login</a>
        </div>
    </div>
</body>
</html>
