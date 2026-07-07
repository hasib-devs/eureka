<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload too large</title>
    <style>
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', Arial, sans-serif; background: #fafafa; color: #1c1c1c; }
        .card { max-width: 460px; background: #fff; border: 1px solid #e8e8e6; border-top: 3px solid #FFCC00; padding: 40px; text-align: center; }
        h1 { font-size: 20px; margin: 0 0 12px; }
        p { font-size: 14px; color: #555; line-height: 1.7; margin: 0 0 24px; }
        button { padding: 14px 32px; background: #0a0a0a; color: #fff; border: none; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; }
        button:hover { opacity: 0.85; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Upload too large</h1>
        <p>Everything you attached adds up to more than the server limit ({{ $limit }}).
           Go back and use a smaller video or fewer/lighter images, then save again —
           nothing else on the form was the problem.</p>
        <button onclick="history.back()">Go Back</button>
    </div>
</body>
</html>
