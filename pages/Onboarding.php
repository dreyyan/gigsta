<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- [IMPORT] CSS: Stylesheet -->
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/onboarding.css">
    <!-- [IMPORT] Website Icon -->
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <!-- [IMPORT] Fonts: Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=DM+Serif+Text&display=swap" rel="stylesheet">
    <title>Onboarding • GIGsta ⚡</title>
</head>
<body>
    <!-- [SECTION] Onboarding -->
    <main class="onboarding-container">
        <form action="ProcessOnboarding.php" method="POST" enctype="multipart/form-data">
            <img id="logo-icon" src="../images/gigsta-logo-minimal.svg" alt="Gigsta Logo">
            <!-- Step 1: Choose Your Role -->
            <div class="onboarding-step" id="step-role">
                <div class="onboarding-header">
                    <div style="display: flex; justify-content: center; gap: 16px;">
                        <h1>Welcome to</h1>
                        <img id="logo-icon" src="../images/gigsta-logo.svg" alt="Gigsta Logo">
                    </div>
                    <h3>Let’s get you set up in under a minute.</h3>
                </div>

                <div class="role-selection">

                    <!-- CLIENT OPTION -->
                    <label class="role-card">
                        <input type="radio" name="role" value="client" required>
                        <div class="role-inner client">
                            <img src="../images/briefcase-icon.svg" alt="Gigsta logo" id="gigsta-logo">
                            <h2>I’m a <strong>Client</strong></h2>
                            <p class="serif">I want to hire talent and get work done fast</p>
                        </div>
                    </label>

                    <!-- GIGSTER OPTION -->
                    <label class="role-card">
                        <input type="radio" name="role" value="gigster" required>
                        <div class="role-inner gigster">
                            <img src="../images/rocket-icon.svg" alt="Gigster Icon" id="gigsta-logo">
                            <h2>I’m a <strong>Gigster</strong></h2>
                            <p class="serif">I want to offer services and earn money</p>
                        </div>
                    </label>

                </div>

                <button type="submit" class="btn-primary">Continue →</button>
            </div>

        </form>

        <!-- Trust line (same as landing) -->
        <div id="trust-content-container" style="margin-top: 60px; opacity: 0.8;">
            <span>Fixed-price • Lightning-fast • </span>
            <span>Proudly localized in Iloilo, Philippines</span>
        </div>

    </main>

    <script>
        // Optional: Add visual feedback when selecting role
        document.querySelectorAll('input[name="role"]').forEach(input => {
            input.addEventListener('change', function() {
                document.querySelectorAll('.role-card').forEach(card => {
                    card.classList.remove('selected');
                });
                this.closest('.role-card').classList.add('selected');
            });
        });
    </script>

</body>
</html>