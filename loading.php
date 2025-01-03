<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .loading-message {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="loading-message">
        <h2>Logging In...</h2>
        <p>Please wait while we log you In.</p>
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Redirect to dashboard after 3 seconds
    setTimeout(function() {
        window.location.href = 'dashboard.php';
    }, 2000);
</script>

</body>
</html>