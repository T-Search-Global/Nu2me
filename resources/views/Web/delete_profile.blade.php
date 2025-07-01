<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Profile — Nu2me</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 60px auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        h1, h2 {
            color: #e53935;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            color: #444;
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        ul {
            padding-left: 20px;
            margin-bottom: 20px;
        }

        .highlight {
            font-weight: bold;
            color: #333;
        }

        .section {
            margin-top: 30px;
        }

        .btns {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
        }

        .btn-cancel {
            background-color: #9e9e9e;
            color: white;
        }

        .btn-delete {
            background-color: #e53935;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Delete Your Nu2me Account</h1>

    <p>We’re sorry to see you go! If you’ve decided that you’d like to permanently close your Nu2me – RC Marketplace account, we want to make the process as clear and simple as possible.</p>

    <p>Please remember that deleting your account means all of your profile information, listings, events, chats, and other data associated with your account will be permanently removed. <strong>This action cannot be undone once confirmed.</strong></p>

    <div class="section">
        <h2>✅ How to Delete Your Account</h2>
        <ul>
            <li>Open the Nu2me app and log in to your account.</li>
            <li>Tap your Profile icon in the app’s navigation menu.</li>
            <li>Scroll to the bottom of the profile page and tap “Delete Account.”</li>
            <li>Follow the on-screen instructions to confirm deletion.</li>
            <li>Once confirmed, all your data will be permanently deleted.</li>
        </ul>
    </div>

    <div class="section">
        <h2>⚠️ What Happens After Deletion</h2>
        <ul>
            <li>Your listings and events will be removed from the marketplace.</li>
            <li>Your profile will no longer be visible to other users.</li>
            <li>Your chat history will be permanently deleted.</li>
            <li>Any active subscriptions or featured listings will be canceled.</li>
            <li>You will lose access to any paid features linked to your account.</li>
        </ul>
    </div>

    <div class="section">
        <h2>💬 Need Assistance?</h2>
        <p>If you encounter issues deleting your account or need help, contact our support team.</p>
        <p><strong>Email:</strong> <a href="mailto:support@nu2merc.com">support@nu2merc.com</a></p>
    </div>

    <div class="section">
        <h2>🤝 We’d Love Your Feedback</h2>
        <p>If you're willing to share, we’d love to hear why you’re leaving. Your feedback helps us improve Nu2me for everyone.</p>
        <p>Thank you for being part of our community. We hope to welcome you back in the future!</p>
    </div>

    {{-- <div class="btns">
        <a href="{{ route('home') }}" class="btn btn-cancel">Cancel</a>
        <form method="POST" action="{{ route('delete.account') }}">
            @csrf
            <button type="submit" class="btn btn-delete">Confirm Delete</button>
        </form>
    </div> --}}
</div>

</body>
</html>
