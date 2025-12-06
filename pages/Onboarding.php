<?php
session_start();
require_once __DIR__ . '/../database/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /../pages/Login.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];

// THIS IS THE ONLY FIX YOU NEED
$stmt = $db->prepare("SELECT role FROM users WHERE id = :id");
$stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
$result = $stmt->execute();
$userRow = $result->fetchArray(SQLITE3_ASSOC);

// If user doesn't exist or query fails → redirect to login
if (!$userRow) {
    session_destroy();
    header("Location: /gigsta/pages/Login.php");
    exit;
}

// This line was crashing because $user was false/null
$hasRole = !empty($userRow['role']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- [IMPORT] CSS: Stylesheet -->
    <link rel="stylesheet" href="/gigsta/css/styles.css">
    <link rel="stylesheet" href="/gigsta/css/primary-button.css">
    <link rel="stylesheet" href="/gigsta/css/onboarding.css">
    <!-- [IMPORT] Website Icon -->
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <!-- [IMPORT] Fonts: Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Complete Your Profile • GIGsta</title>
</head>
<body>

<main class="onboarding-container">
    <?php if ($hasRole): ?>
        <!-- Already onboarded -->
        <div class="onboarding-header">
            <h1>Welcome back!</h1>
            <h3>Your profile is all set</h3>
            <a href="/gigsta/pages/BrowseGigs.php" class="btn-primary">Browse Gigs →</a>
        </div>
    <?php else: ?>
        <!-- ONBOARDING FLOW -->
        <form action="/gigsta/database/process_onboarding.php" method="POST" enctype="multipart/form-data">

            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="progress-step active">1</div>
                <div class="progress-line"></div>
                <div class="progress-step">2</div>
                <div class="progress-line"></div>
                <div class="progress-step">3</div>
                <div class="progress-line"></div>
                <div class="progress-step">4</div>
                <div class="progress-line"></div>
                <div class="progress-step">5</div>
            </div>

            <div class="onboarding-header">
                <h1>Let’s get you set up</h1>
                <h3>Takes less than a minute</h3>
            </div>

            <!-- STEP 1: Role -->
            <div class="onboarding-step active" data-step="1">
                <div class="role-selection">
                    <label class="role-card">
                        <input type="radio" name="role" value="client" required>
                        <div class="role-inner client">
                            <img src="/gigsta/images/briefcase-icon.svg" alt="Client">
                            <h2>I’m a <strong>Client</strong></h2>
                            <p>I want to hire talent and get work done</p>
                        </div>
                    </label>

                    <label class="role-card">
                        <input type="radio" name="role" value="gigster" required>
                        <div class="role-inner gigster">
                            <img src="/gigsta/images/rocket-icon.svg" alt="Gigster">
                            <h2>I’m a <strong>Gigster</strong></h2>
                            <p>I want to offer services and earn money</p>
                        </div>
                    </label>
                </div>
                <button type="button" class="btn-next">Next &nbsp;&nbsp;→</button>
            </div>

            <!-- STEP 2: Age -->
            <div class="onboarding-step" data-step="2">
                <h2>How old are you?</h2>
                <input type="number" name="age" min="13" max="100" placeholder="e.g. 25" required class="input-field">
                <div class="nav-buttons">
                    <button type="button" class="btn-back">← &nbsp;&nbsp;Back</button>
                    <button type="button" class="btn-next">Next &nbsp;&nbsp;→</button>
                </div>
            </div>

            <!-- STEP 3: Location -->
            <div class="onboarding-step" data-step="3">
                <h2>Where are you based?</h2>
                <input type="text" name="location" placeholder="e.g. Iloilo City, Philippines" required class="input-field">
                <div class="nav-buttons">
                    <button type="button" class="btn-back">← &nbsp;&nbsp;Back</button>
                    <button type="button" class="btn-next">Next &nbsp;&nbsp;→</button>
                </div>
            </div>

            <!-- STEP 4: Experience -->
            <div class="onboarding-step" data-step="4">
                <h2>How many years of experience do you have?</h2>
                <input type="number" name="experience_years" min="0" max="50" placeholder="e.g. 5" required class="input-field">
                <div class="nav-buttons">
                    <button type="button" class="btn-back">← &nbsp;&nbsp;Back</button>
                    <button type="button" class="btn-next">Next &nbsp;&nbsp;→</button>
                </div>
            </div>

            <!-- STEP 5: Talent Tags (Gigster only) -->
            <div class="onboarding-step" data-step="5">
                <h2>What are your skills?</h2>
                <p>Select all that apply (you can change later)</p>
                <div class="tags-container">
                    <?php
                    $tags = ['Producer', 'Rapper', 'Developer', 'Designer', 'Video Editor', 'Writer', 'Marketer', 'Photographer', 'Voice Actor', 'Animator'];
                    foreach ($tags as $tag):
                    ?>
                        <label class="tag-label">
                            <input type="checkbox" name="tags[]" value="<?= $tag ?>">
                            <span class="tag-chip"><?= $tag ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <div class="nav-buttons">
                    <button type="button" class="btn-back">← &nbsp;&nbsp;Back</button>
                    <button type="submit" class="btn-primary">Complete Setup →</button>
                </div>
            </div>
        </form>
    <?php endif; ?>

    <div id="trust-content-container">
        <span>Fixed-price • Lightning-fast • </span>
        <span>Proudly localized in Iloilo, Philippines</span>
    </div>
</main>

<script>
    const steps = document.querySelectorAll('.onboarding-step');
    const nextBtns = document.querySelectorAll('.btn-next');
    const backBtns = document.querySelectorAll('.btn-back');
    let currentStep = 1;

    function showStep(n) {
        steps.forEach((step, i) => {
            step.classList.toggle('active', i + 1 === n);
            document.querySelectorAll('.progress-step')[i].classList.toggle('active', i < n);
        });
    }

    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep < 5) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });

    backBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    // Role selection visual feedback
    document.querySelectorAll('input[name="role"]').forEach(input => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.toggle('selected', card.querySelector('input').checked);
            });
        });
    });
</script>
</body>
</html>