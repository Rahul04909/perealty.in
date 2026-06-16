<?php
/**
 * Modern Apartment Promo Component for Prime Edge Realty
 */
?>
<section class="promo-section" id="promo">
    <div class="container">
        <div class="promo-grid grid-2">
            <!-- Left Side: Informative & CEO testimonial panel -->
            <div class="promo-info-pane">
                <span class="section-tagline tagline-dark">HIGHLIGHTED PROPERTY</span>
                <h2 class="section-title text-dark">Take A Look At Our Modern Apartment</h2>
                <p class="promo-desc">
                    We are a real estate firm with over 20 years of expertise, and our main goal is to provide amazing locations to our partners and clients. Within the luxury real estate market, our agency offers customized solutions.
                </p>
                <div class="promo-action">
                    <a href="#contact" class="btn btn-dark">
                        Request A Visit <i data-lucide="arrow-right" class="gold-arrow"></i>
                    </a>
                </div>

                <!-- Testimonial CEO signature block -->
                <div class="ceo-profile-card">
                    <div class="ceo-avatar-box">
                        <img src="assets/images/agent_portrait.png" alt="Basila Smith, CEO of Prime Edge Realty">
                    </div>
                    <div class="ceo-meta">
                        <h4 class="ceo-name">Basila Smith</h4>
                        <p class="ceo-title">CEO of Prime Edge</p>
                    </div>
                    <div class="ceo-signature-box">
                        <!-- Handwritten Signature in SVG -->
                        <svg viewBox="0 0 120 50" class="signature-svg" aria-label="Basila Smith Signature">
                            <path d="M 10 30 Q 25 10 35 25 T 50 15 T 65 35 Q 75 10 90 20 T 110 15" fill="none" stroke="#121E21" stroke-width="2" stroke-linecap="round" />
                            <path d="M 25 25 L 95 25" fill="none" stroke="#121E21" stroke-width="1" stroke-dasharray="3 3" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Right Side: Luxury image overlay play trigger -->
            <div class="promo-visual-pane">
                <div class="promo-image-wrapper custom-border-img">
                    <img src="assets/images/promo_apartment.png" alt="Premium Modern Apartment Exterior" class="promo-img">
                    
                    <!-- Bottom floating play button -->
                    <button class="promo-play-btn" id="promo-video-trigger" aria-label="Play property tour video">
                        <i data-lucide="play" fill="white"></i>
                    </button>
                    
                    <!-- Embedded Video Player Container -->
                    <div class="video-player-iframe" id="promo-video-iframe-container">
                        <span class="close-video" id="promo-video-iframe-close"><i data-lucide="x"></i></span>
                        <iframe id="promo-youtube-video" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
