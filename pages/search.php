<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Search Results - Gigsta</title>
</head>

<body>

    <!---- HEADER (Same as index.php) -->
    <header>
        <div class="logo">
            <img src="../images/gigsta-logo.svg">
        </div>

        <div class="header-btns">  
            <div class="header-links">
                <a href="#">Explore</a>
                <a href="#">Become a Freelancer</a>
            </div>

            <div class="header-links">
                <a href="#">Sign In</a>
                <a id="primary-btn" href="#">Join</a>
            </div>
        </div>
    </header>

    <!--- MAIN SEARCH RESULTS -->
    <main class="search-results-container">

        <!-- FILTERS AND RESULTS COUNT -->
        <div class="filters-section">
            <div class="filter-buttons">
                <button class="filter-btn">Budget <img src="https://via.placeholder.com/12x12?text=v" alt="dropdown" class="dropdown-icon"></button>
                <button class="filter-btn">Delivery Time <img src="https://via.placeholder.com/12x12?text=v" alt="dropdown" class="dropdown-icon"></button>
            </div>

            <div class="sort-section">
                <span>Sort by: <strong>Best selling</strong> <img src="https://via.placeholder.com/12x12?text=v" alt="dropdown" class="dropdown-icon"></span>
            </div>
        </div>

        <!-- RESULTS COUNT -->
        <div class="results-count">
            <p>9 results</p>
        </div>

        <!-- GIG LISTINGS GRID -->
        <div class="gigs-grid">

            <!-- GIG CARD 1 -->
            <div class="gig-card">
                <div class="gig-image">
                    <img src="https://via.placeholder.com/250x200?text=Gig+Image" alt="Gig">
                </div>
                <div class="gig-info">
                    <div class="gig-header">
                        <p class="gig-seller">John Doe</p>
                        <span class="pro-badge"><img src="https://via.placeholder.com/16x16?text=star" alt="pro" class="badge-icon"> Gigsta Pro</span>
                    </div>
                    <p class="gig-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...</p>
                    <div class="gig-footer">
                        <div class="rating">
                            <img src="https://via.placeholder.com/16x16?text=star" alt="star" class="rating-star">
                            <span class="rating-value">5.0</span>
                            <span class="rating-count">(420)</span>
                        </div>
                        <p class="gig-price">From $1000</p>
                    </div>
                </div>
            </div>

            <!-- GIG CARD 2 -->
            <div class="gig-card">
                <div class="gig-image">
                    <img src="https://via.placeholder.com/250x200?text=Gig+Image" alt="Gig">
                </div>
                <div class="gig-info">
                    <div class="gig-header">
                        <span class="gig-seller">John Doe</span>
                        <span class="pro-badge"><img src="https://via.placeholder.com/16x16?text=star" alt="pro" class="badge-icon"> Gigsta Pro</span>
                    </div>
                    <p class="gig-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...</p>
                    <div class="gig-footer">
                        <div class="rating">
                            <img src="https://via.placeholder.com/16x16?text=star" alt="star" class="rating-star">
                            <span class="rating-value">5.0</span>
                            <span class="rating-count">(420)</span>
                        </div>
                        <p class="gig-price">From $1000</p>
                    </div>
                </div>
            </div>

            <!-- GIG CARD 3 -->
            <div class="gig-card">
                <div class="gig-image">
                    <img src="https://via.placeholder.com/250x200?text=Gig+Image" alt="Gig">
                </div>
                <div class="gig-info">
                    <div class="gig-header">
                        <span class="gig-seller">John Doe</span>
                        <span class="pro-badge"><img src="https://via.placeholder.com/16x16?text=star" alt="pro" class="badge-icon"> Gigsta Pro</span>
                    </div>
                    <p class="gig-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...</p>
                    <div class="gig-footer">
                        <div class="rating">
                            <img src="https://via.placeholder.com/16x16?text=star" alt="star" class="rating-star">
                            <span class="rating-value">5.0</span>
                            <span class="rating-count">(420)</span>
                        </div>
                        <p class="gig-price">From $1000</p>
                    </div>
                </div>
            </div>

            <!-- GIG CARD 4 -->
            <div class="gig-card">
                <div class="gig-image">
                    <img src="https://via.placeholder.com/250x200?text=Gig+Image" alt="Gig">
                </div>
                <div class="gig-info">
                    <div class="gig-header">
                        <span class="gig-seller">John Doe</span>
                        <span class="pro-badge"><img src="https://via.placeholder.com/16x16?text=star" alt="pro" class="badge-icon"> Gigsta Pro</span>
                    </div>
                    <p class="gig-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...</p>
                    <div class="gig-footer">
                        <div class="rating">
                            <img src="https://via.placeholder.com/16x16?text=star" alt="star" class="rating-star">
                            <span class="rating-value">5.0</span>
                            <span class="rating-count">(420)</span>
                        </div>
                        <p class="gig-price">From $1000</p>
                    </div>
                </div>
            </div>

            <!-- GIG CARD 5 -->
            <div class="gig-card">
                <div class="gig-image">
                    <img src="https://via.placeholder.com/250x200?text=Gig+Image" alt="Gig">
                </div>
                <div class="gig-info">
                    <div class="gig-header">
                        <span class="gig-seller">John Doe</span>
                        <span class="pro-badge"><img src="https://via.placeholder.com/16x16?text=star" alt="pro" class="badge-icon"> Gigsta Pro</span>
                    </div>
                    <p class="gig-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...</p>
                    <div class="gig-footer">
                        <div class="rating">
                            <img src="https://via.placeholder.com/16x16?text=star" alt="star" class="rating-star">
                            <span class="rating-value">5.0</span>
                            <span class="rating-count">(420)</span>
                        </div>
                        <p class="gig-price">From $1000</p>
                    </div>
                </div>
            </div>

            <!-- GIG CARD 6 -->
            <div class="gig-card">
                <div class="gig-image">
                    <img src="https://via.placeholder.com/250x200?text=Gig+Image" alt="Gig">
                </div>
                <div class="gig-info">
                    <div class="gig-header">
                        <span class="gig-seller">John Doe</span>
                        <span class="pro-badge"><img src="https://via.placeholder.com/16x16?text=star" alt="pro" class="badge-icon"> Gigsta Pro</span>
                    </div>
                    <p class="gig-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...</p>
                    <div class="gig-footer">
                        <div class="rating">
                            <img src="https://via.placeholder.com/16x16?text=star" alt="star" class="rating-star">
                            <span class="rating-value">5.0</span>
                            <span class="rating-count">(420)</span>
                        </div>
                        <p class="gig-price">From $1000</p>
                    </div>
                </div>
            </div>

            <!-- GIG CARD 7 -->
            <div class="gig-card">
                <div class="gig-image">
                    <img src="https://via.placeholder.com/250x200?text=Gig+Image" alt="Gig">
                </div>
                <div class="gig-info">
                    <div class="gig-header">
                        <span class="gig-seller">John Doe</span>
                        <span class="pro-badge"><img src="https://via.placeholder.com/16x16?text=star" alt="pro" class="badge-icon"> Gigsta Pro</span>
                    </div>
                    <p class="gig-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...</p>
                    <div class="gig-footer">
                        <div class="rating">
                            <img src="https://via.placeholder.com/16x16?text=star" alt="star" class="rating-star">
                            <span class="rating-value">5.0</span>
                            <span class="rating-count">(420)</span>
                        </div>
                        <p class="gig-price">From $1000</p>
                    </div>
                </div>
            </div>

            <!-- GIG CARD 8 -->
            <div class="gig-card">
                <div class="gig-image">
                    <img src="https://via.placeholder.com/250x200?text=Gig+Image" alt="Gig">
                </div>
                <div class="gig-info">
                    <div class="gig-header">
                        <span class="gig-seller">John Doe</span>
                        <span class="pro-badge"><img src="https://via.placeholder.com/16x16?text=star" alt="pro" class="badge-icon"> Gigsta Pro</span>
                    </div>
                    <p class="gig-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor...</p>
                    <div class="gig-footer">
                        <div class="rating">
                            <img src="https://via.placeholder.com/16x16?text=star" alt="star" class="rating-star">
                            <span class="rating-value">5.0</span>
                            <span class="rating-count">(420)</span>
                        </div>
                        <p class="gig-price">From $1000</p>
                    </div>
                </div>
            </div>

                </div>
            </div>

        </div>

    </main>

</body>
</html>