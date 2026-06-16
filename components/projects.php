<?php
/**
 * Projects Component for Prime Edge Realty
 */
?>
<section class="projects-section" id="projects">
    <!-- Huge background outline text -->
    <div class="outline-text projects-bg-text">PROJECTS</div>

    <div class="container projects-container-wrapper">
        <div class="projects-layout-grid">
            <!-- Left Side: Navigation Details & Info -->
            <div class="projects-info-pane">
                <!-- Vertical index indicator -->
                <div class="projects-index-indicator">
                    <span class="index-line-track"></span>
                    <span class="index-number-bubble" id="project-active-num">01</span>
                </div>

                <div class="projects-text-content">
                    <span class="section-tagline">EXQUISITE WORK</span>
                    <h2 class="section-title">Discover Modern Living At Prime Edge Residence.</h2>
                    <p class="projects-desc">
                        Residence takes advantage of abundant sunlight by incorporating solar panels and smart automation into its architecture.
                    </p>
                    <a href="#contact" class="btn btn-primary">
                        Explore More <i data-lucide="arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Right Side: Project Cards Showcase Slider -->
            <div class="projects-showcase-pane">
                <div class="projects-slider-wrapper" id="projects-slider">
                    <!-- Project Slide 1 -->
                    <div class="project-slide active" data-index="01">
                        <div class="project-card-image custom-border-img">
                            <img src="assets/images/project_one.png" alt="Prime Edge Eco Residence">
                            <div class="project-card-overlay">
                                <h4 class="project-card-title">Eco-Solar Villa</h4>
                                <p class="project-card-location">Beverly Hills, CA</p>
                            </div>
                            <a href="#contact" class="project-card-btn" aria-label="View Details">
                                <i data-lucide="arrow-up-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Project Slide 2 -->
                    <div class="project-slide" data-index="02">
                        <div class="project-card-image custom-border-img">
                            <img src="assets/images/project_two.png" alt="Prime Edge Cubic Villa">
                            <div class="project-card-overlay">
                                <h4 class="project-card-title">Cubic Glass Manor</h4>
                                <p class="project-card-location">Malibu, CA</p>
                            </div>
                            <a href="#contact" class="project-card-btn" aria-label="View Details">
                                <i data-lucide="arrow-up-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Project Slide 3 -->
                    <div class="project-slide" data-index="03">
                        <div class="project-card-image custom-border-img">
                            <img src="assets/images/hero_house.png" alt="Prime Edge Luxury Mansion">
                            <div class="project-card-overlay">
                                <h4 class="project-card-title">Contemporary Mansion</h4>
                                <p class="project-card-location">Miami, FL</p>
                            </div>
                            <a href="#contact" class="project-card-btn" aria-label="View Details">
                                <i data-lucide="arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bottom Horizontal Indicators -->
                <div class="projects-slider-nav">
                    <span class="slide-nav-dot active" data-slide="0"></span>
                    <span class="slide-nav-dot" data-slide="1"></span>
                    <span class="slide-nav-dot" data-slide="2"></span>
                </div>
            </div>
        </div>
    </div>
</section>
