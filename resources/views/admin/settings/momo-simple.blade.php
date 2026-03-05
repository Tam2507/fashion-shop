<!DOCTYPE html>
<html>
<head>
    <title>Test MoMo Settings</title>
</head>
<body>
    <h1>MoMo Settings Test</h1>
    <p>If you see this, the route and controller work!</p>
    <p>APP_KEY exists: {{ config('app.key') ? 'YES' : 'NO' }}</p>
    <p>MOMO_STATIC_QR: {{ config('app.momo_static_qr', 'NOT SET') }}</p>
</body>
</html>
