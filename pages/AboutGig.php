<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gigsta: About Gig</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/aboutgig.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/rating.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>

<body>
<!-- [COMPONENT] Header -->
    <?php include '../components/Header.php'; ?>
<div class="gig-container">

    <!-- LEFT COLUMN -->
    <div class="gig-left">

        <!-- GALLERY + INFO -->
        <div class="gig-top">
            <div class="gallery">
                <img src="../images/gig-image.jpg" class="main-image" alt="Gig Image">
                <div class="thumbs">
                    <img src="../images/gig-image.jpg" class="thumb">
                    <img src="../images/gig-image.jpg" class="thumb">
                    <img src="../images/gig-image.jpg" class="thumb">
                </div>
            </div>

            <div class="gig-info">
                 <!-- gigger Info -->
                <div class="gigger-info">
                    <img src="../images/kirk.jpeg" class="gigger-img" alt="Profile">
                    <div class="gigger-text">
                        <h5>John Doe</h5>
                        <div class="gigger-rating">
                            <div class="star"></div>
                            <span>5.0 (420)</span>
                        </div>
                    </div>
                </div>
                <h4>Website Developer</h4>
                <p class="gig-desc">
                    I will create a lorem ipsum dolor sit amet, consectetur adipiscing elit...
                </p>
                <div class="price">From <strong>$1000</strong></div>
                <button class="contact-btn">Contact Me</button>
            </div>
        </div>

        <!-- REVIEWS -->
        <h3>Reviews</h3>
        <div class="reviews">
            <div class="review-card">
                <div class="review-header">
                    <img src="path/to/profile.jpg" class="review-img">
                    <div>
                        <h4>John Doe</h4>
                        <p>@johndoe_01</p>
                    </div>
                    <div class="stars">
                        <div class="star"></div>
                        <div class="star"></div>
                        <div class="star"></div>
                        <div class="star"></div>
                        <div class="star"></div>
                    </div>
                </div>
                <p class="review-text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit...
                </p>
            </div>
        </div>

        <!-- SIMILAR GIGS -->
        <h3>Similar Gigs</h3>
        <div class="similar">
            <div class="sim-card">
                <div class="sim-img"></div>
                <div class="sim-info">
                    <p class="sim-title">John Doe</p>
                    <p class="sim-desc">Lorem ipsum dolor sit amet...</p>
                    <div class="sim-footer">
                        <span>5.0 (420)</span>
                        <span>$1000</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- RIGHT SIDEBAR -->
    <div class="gig-right">
        <div class="gig-sidebar">
            <h4>Gigster's Profile</h4>
            <img src="../images/kirk.jpeg" class="sidebar-img" alt="Profile">
            <h4>John Doe</h4>
            <p class="sidebar-desc">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit...
            </p>
            <div class="sidebar-info">
                <p><img src="path/to/user.svg" class="sidebar-icon"> 40 years old</p>
                <p><img src="path/to/location.svg" class="sidebar-icon"> Berlin, Germany</p>
                <p><img src="path/to/briefcase.svg" class="sidebar-icon"> 5 Years Experience</p>
            </div>
            <div class="sidebar-tags">
                <span class="tag producer">Producer</span>
                <span class="tag rapper">Rapper</span>
                <span class="tag programmer">Programmer</span>
                <span class="tag videographer">Videographer</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
