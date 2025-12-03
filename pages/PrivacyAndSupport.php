<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Gigsta • Privacy Policy & Support</title>

    <style>
        .privacy-support-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 80px 40px;
            line-height: 1.7;
        }

        .page-title {
            font-family: var(--header);
            font-weight: 700;
            font-size: 48px;
            letter-spacing: -1.5%;
            color: var(--text);
            margin-bottom: 16px;
            text-align: center;
        }

        .page-subtitle {
            font-family: var(--body);
            font-size: 20px;
            color: var(--neutral);
            text-align: center;
            max-width: 720px;
            margin: 0 auto 64px auto;
        }

        .section {
            margin-bottom: 80px;
        }

        .section h2 {
            font-family: var(--header);
            font-weight: 600;
            font-size: 32px;
            color: var(--text);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
        }

        .section h3 {
            font-family: var(--header);
            font-weight: 600;
            font-size: 24px;
            color: var(--text);
            margin: 32px 0 16px 0;
        }

        .section p,
        .section li {
            font-family: var(--body);
            font-size: 17px;
            color: var(--text);
            margin-bottom: 16px;
        }

        .section ul {
            padding-left: 28px;
            margin-bottom: 24px;
        }

        .section ul li {
            margin-bottom: 12px;
            position: relative;
        }

        .section ul li::marker {
            color: var(--primary);
        }

        .highlight-box {
            background: var(--secondary);
            border-left: 5px solid var(--primary);
            padding: 24px;
            border-radius: 8px;
            margin: 32px 0;
            font-size: 17px;
            color: var(--text);
        }

        .contact-support {
            background: var(--background);
            border: 2px solid var(--primary);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            margin: 48px 0;
        }

        .contact-support h3 {
            font-size: 28px;
            margin-bottom: 16px;
        }

        .contact-support p {
            font-size: 18px;
            color: var(--neutral);
            margin-bottom: 32px;
        }

        .btn-support {
            display: inline-block;
            background: var(--primary);
            color: white;
            font-family: var(--header);
            font-weight: 600;
            font-size: 18px;
            padding: 14px 32px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-support:hover {
            background: var(--accent);
            transform: translateY(-2px);
        }

        .last-updated {
            text-align: center;
            color: var(--neutral);
            font-size: 15px;
            margin-top: 80px;
        }
    </style>
</head>
<body>

    <?php include '../components/Header.php'; ?>

    <div class="privacy-support-container">

        <h1 class="page-title">Privacy Policy & Support</h1>
        <p class="page-subtitle">
            Your trust is everything to us. Here’s exactly how we protect your data and how you can get help whenever you need it.
        </p>

        <!-- PRIVACY POLICY -->
        <div class="section">
            <h2>Privacy Policy</h2>
            <p><strong>Effective Date: December 1, 2025</strong></p>

            <p>At <strong>Gigsta</strong>, we respect your privacy and are committed to protecting your personal information. This policy explains what data we collect, how we use it, and your rights.</p>

            <h3>1. Information We Collect</h3>
            <ul>
                <li>Account info (name, email, profile picture)</li>
                <li>Payment details (processed securely via our payment partners)</li>
                <li>Messages and files shared in chats</li>
                <li>Usage data (pages visited, clicks, time spent)</li>
                <li>Device and browser information</li>
            </ul>

            <h3>2. How We Use Your Data</h3>
            <ul>
                <li>To provide and improve our services</li>
                <li>To facilitate transactions between clients and freelancers</li>
                <li>To send important notifications (order updates, security alerts)</li>
                <li>To prevent fraud and abuse</li>
                <li>To analyze usage and improve the platform</li>
            </ul>

            <div class="highlight-box">
                <strong>We never sell your personal data.</strong><br>
                Your messages and files are private and only visible to you and the person you're chatting with.
            </div>

            <h3>3. Data Security</h3>
            <p>We use industry-standard encryption (TLS/SSL) for all data in transit. Your files and payments are processed through trusted partners (Stripe, PayPal) that meet the highest security standards.</p>

            <h3>4. Your Rights</h3>
            <ul>
                <li>Access or download your data at any time</li>
                <li>Request deletion of your account and data</li>
                <li>Opt out of marketing emails</li>
                <li>Be forgotten under applicable laws (e.g. GDPR, Philippines Data Privacy Act)</li>
            </ul>

            <h3>5. Contact Us About Privacy</h3>
            <p>Email us anytime at: <strong>privacy@gigsta.ph</strong></p>
        </div>

        <!-- SUPPORT & HELP -->
        <div class="section">
            <h2>Need Help? We’ve Got You</h2>

            <div class="contact-support">
                <h3>We’re here 7 days a week</h3>
                <p>Most replies within <strong>1 hour</strong> — even on weekends.<br>Proudly based in <strong>Iloilo City, Philippines</strong></p>
                <a href="mailto:support@gigsta.ph" class="btn-support">Email Support →</a>
            </div>

            <h3>Common Questions</h3>
            <ul>
                <li><strong>How do I report a problem with an order?</strong><br>Go to your order page → click “Contact Support”</li>
                <li><strong>Can I get a refund?</strong><br>Yes! Full refund if the freelancer hasn’t started or delivered.</li>
                <li><strong>Is my payment secure?</strong><br>100%. We never store your card details.</li>
                <li><strong>How do I delete my account?</strong><br>Email us at support@gigsta.ph with your request.</li>
            </ul>
        </div>

        <p class="last-updated">
            Last updated: December 3, 2025
        </p>

    </div>

</body>
</html>