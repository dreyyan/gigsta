<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>John Doe - Client Profile</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <!-- Blank Header as requested -->
    <header id="header" style="height: 60px; border-bottom: 2px solid var(--neutral); opacity: 0.2;"></header>

    <main class="client-profile-container">
        <!-- Profile Card (single card with border) -->
        <section class="profile-card">
            <div class="profile-card-content">
                <!-- Left: Profile Picture (CIRCLE) -->
                <div class="profile-picture-container">
                    <img src="../images/profile-picture.jfif" alt="John Doe" class="profile-picture">
                </div>
                
                <!-- Right: Profile Info -->
                <div class="profile-info-container">
                    <div class="name-rating-row">
                        <h1>John Doe</h1>
                        <div class="rating-display">
                             <img src="../images/star-rating-icon.svg" alt="Star" class="star-icon">
                            <strong>5.0</strong> <span class="rating-count">(420)</span>
                        </div>
                    </div>
                    
                    <p class="profile-bio">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident nam laudantium ipsum est molestias tenetur voluptas facere eos pariatur qui voluptatibus similique quia, praesentium...
                    </p>
                    
                    <div class="profile-details">
                        <div class="detail-row">
                            <img src="../images/age-icon.svg" alt="Age" class="detail-icon">
                            <strong>40 years old</strong>
                        </div>
                        <div class="detail-row">
                            <img src="../images/location-icon.svg" alt="Location" class="detail-icon">
                            <strong>Berlin, Germany</strong>
                        </div>
                        <div class="detail-row">
                            <img src="../images/years-experience-icon.svg" alt="Years Experience" class="detail-icon">
                            <strong>5 Years</strong>
                    </div>
                    
                    <div class="skills-row">
                        <span class="skill-item">Producer</span>
                        <span class="skill-item">Rapper</span>
                        <span class="skill-item">Programmer</span>
                        <span class="skill-item">Videographer</span>
                    </div>
                    
                    <button class="contact-btn">
                        Contact
                    </button>
                </div>
            </div>
        </section>
        
       <!-- Recent Gigs Section -->
<section class="recent-gigs-section">
    <h2>Recent Gigs</h2>
    
    <div class="gigs-grid">
        <!-- Gig 1 -->
        <div class="gig-card">
            <div class="gig-image">
                <img src="assets/images/gigs/gig-1.jpg" alt="Gig 1" 
                     onerror="this.onerror=null; this.classList.add('image-failed');">
            </div>
            <div class="gig-content">
                <div class="gig-title-row">
                    <div class="gig-profile">
                        <img src="../images/profile-picture.jfif" alt="pfp" class="gig-client-pic">
                        <h4>John Doe</h4>
                    </div>
                </div>
                <p class="gig-desc">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...
                </p>
                <div class="gig-footer">
                    <div class="gig-rating">
                        <img src="../images/star-rating-icon.svg" alt="Star" class="gig-star-icon">
                        <span class="gig-rating-text">5.0</span>
                    </div>
                    <span class="gig-amount">$1000</span>
                </div>
            </div>
        </div>
        
        <!-- Gig 2 -->
        <div class="gig-card">
            <div class="gig-image">
                <img src="assets/images/gigs/gig-2.jpg" alt="Gig 2" 
                     onerror="this.onerror=null; this.classList.add('image-failed');">
            </div>
            <div class="gig-content">
                <div class="gig-title-row">
                    <div class="gig-profile">
                        <img src="../images/profile-picture.jfif" alt="pfp" class="gig-client-pic">
                        <h4>John Doe</h4>
                    </div>
                </div>
                <p class="gig-desc">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...
                </p>
                <div class="gig-footer">
                    <div class="gig-rating">
                        <img src="../images/star-rating-icon.svg" alt="Star" class="gig-star-icon">
                        <span class="gig-rating-text">5.0</span>
                    </div>
                    <span class="gig-amount">$1000</span>
                </div>
            </div>
        </div>
        
        <!-- Gig 3 -->
        <div class="gig-card">
            <div class="gig-image">
                <img src="assets/images/gigs/gig-3.jpg" alt="Gig 3" 
                     onerror="this.onerror=null; this.classList.add('image-failed');">
            </div>
            <div class="gig-content">
                <div class="gig-title-row">
                    <div class="gig-profile">
                        <img src="../images/profile-picture.jfif" alt="pfp" class="gig-client-pic">
                        <h4>John Doe</h4>
                    </div>
                </div>
                <p class="gig-desc">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...
                </p>
                <div class="gig-footer">
                    <div class="gig-rating">
                        <img src="../images/star-rating-icon.svg" alt="Star" class="gig-star-icon">
                        <span class="gig-rating-text">5.0</span>
                    </div>
                    <span class="gig-amount">$1000</span>
                </div>
            </div>
        </div>
        
        <!-- Gig 4 -->
        <div class="gig-card">
            <div class="gig-image">
                <img src="assets/images/gigs/gig-4.jpg" alt="Gig 4" 
                     onerror="this.onerror=null; this.classList.add('image-failed');">
            </div>
            <div class="gig-content">
                <div class="gig-title-row">
                    <div class="gig-profile">
                        <img src="../images/profile-picture.jfif" alt="pfp" class="gig-client-pic">
                        <h4>John Doe</h4>
                    </div>
                </div>
                <p class="gig-desc">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...
                </p>
                <div class="gig-footer">
                    <div class="gig-rating">
                        <img src="../images/star-rating-icon.svg" alt="Star" class="gig-star-icon">
                        <span class="gig-rating-text">5.0</span>
                    </div>
                    <span class="gig-amount">$1000</span>
                </div>
            </div>
        </div>
    </div>
</section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        });
    </script>
</body>
</html>