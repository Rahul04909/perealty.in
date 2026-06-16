<?php
/**
 * Footer Component for Prime Edge Realty
 */
?>
    <!-- Footer Section -->
    <footer class="main-footer">
        <div class="container footer-grid">
            <!-- Footer Brand & Info -->
            <div class="footer-col brand-col">
                <a href="index.php" class="logo-area footer-logo">
                    <img src="assets/logo/logo.png" alt="Prime Edge Realty Logo" class="logo-img">
                    <span class="logo-text">
                        <span class="logo-title">PRIME EDGE</span>
                        <span class="logo-subtitle">REALTY</span>
                    </span>
                </a>
                <p class="footer-desc">
                    Prime Edge Realty is a premier real estate firm dedicated to delivering your edge in smart investments. We turn property search into a personalized luxury experience.
                </p>
                <div class="footer-socials">
                    <a href="#" class="social-icon" aria-label="Facebook"><i data-lucide="facebook"></i></a>
                    <a href="#" class="social-icon" aria-label="Twitter"><i data-lucide="twitter"></i></a>
                    <a href="#" class="social-icon" aria-label="Instagram"><i data-lucide="instagram"></i></a>
                    <a href="#" class="social-icon" aria-label="LinkedIn"><i data-lucide="linkedin"></i></a>
                </div>
            </div>

            <!-- Footer Links (Services) -->
            <div class="footer-col links-col">
                <h4 class="footer-title">Services</h4>
                <ul class="footer-links-list">
                    <li><a href="#about"><i data-lucide="chevron-right"></i> Property Valuation</a></li>
                    <li><a href="#about"><i data-lucide="chevron-right"></i> Property Management</a></li>
                    <li><a href="#about"><i data-lucide="chevron-right"></i> Investment Opportunities</a></li>
                    <li><a href="#projects"><i data-lucide="chevron-right"></i> Residential Development</a></li>
                    <li><a href="#projects"><i data-lucide="chevron-right"></i> Commercial Leasing</a></li>
                </ul>
            </div>

            <!-- Footer Links (Quick Links) -->
            <div class="footer-col links-col">
                <h4 class="footer-title">Quick Links</h4>
                <ul class="footer-links-list">
                    <li><a href="#hero"><i data-lucide="chevron-right"></i> Home</a></li>
                    <li><a href="#about"><i data-lucide="chevron-right"></i> About Us</a></li>
                    <li><a href="#projects"><i data-lucide="chevron-right"></i> Properties</a></li>
                    <li><a href="#testimonials"><i data-lucide="chevron-right"></i> Testimonials</a></li>
                    <li><a href="#contact"><i data-lucide="chevron-right"></i> Contact Us</a></li>
                </ul>
            </div>

            <!-- Footer Newsletter -->
            <div class="footer-col newsletter-col">
                <h4 class="footer-title">Newsletter</h4>
                <p class="newsletter-desc">Subscribe to receive the latest premium property listings and smart investment guides.</p>
                <form class="newsletter-form" id="newsletter-subscription" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
                    <div class="newsletter-input-group">
                        <input type="email" placeholder="Your Email Address" required class="newsletter-input">
                        <button type="submit" class="newsletter-btn" aria-label="Subscribe">
                            <i data-lucide="send"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Copyright Bar -->
        <div class="footer-bottom">
            <div class="container footer-bottom-container">
                <p class="copyright-text">&copy; <?php echo date("Y"); ?> Prime Edge Realty. All rights reserved. | Tagline: Your Edge in Smart Investments</p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#hero" class="back-to-top" id="back-to-top-btn" aria-label="Back to top">
        <i data-lucide="arrow-up"></i>
    </a>

    <!-- Global JavaScript File -->
    <script src="assets/js/main.js"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
