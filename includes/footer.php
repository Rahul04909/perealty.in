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
                    <img src="assets/logo/logo.png" alt="<?php echo htmlspecialchars(env('APP_NAME', 'Prime Edge Realiity')); ?> Logo" class="logo-img">
                    <span class="logo-text">
                        <span class="logo-title"><?php 
                            $appName = env('APP_NAME', 'Prime Edge Realiity');
                            $words = explode(' ', $appName);
                            echo htmlspecialchars(strtoupper($words[0] . (isset($words[1]) ? ' ' . $words[1] : '')));
                        ?></span>
                        <span class="logo-subtitle"><?php 
                            echo htmlspecialchars(strtoupper(isset($words[2]) ? $words[2] : ''));
                        ?></span>
                    </span>
                </a>
                <p class="footer-desc">
                    <?php echo htmlspecialchars(env('APP_NAME', 'Prime Edge Realiity')); ?> is a premier real estate firm dedicated to delivering <?php echo htmlspecialchars(strtolower(env('APP_TAGLINE', 'Your Edge in Smart Investments'))); ?>. We turn property search into a personalized luxury experience.
                </p>
                <div class="footer-socials">
                    <a href="#" class="social-icon" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="LinkedIn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                </div>
            </div>

            <!-- Footer Links (Office Info) -->
            <div class="footer-col office-col">
                <h4 class="footer-title">Office Info</h4>
                <ul class="footer-links-list">
                    <li style="display: flex; gap: 0.75rem; align-items: flex-start; line-height: 1.5;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--color-accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 4px;"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <a href="https://maps.google.com/?q=<?php echo urlencode(env('CONTACT_ADDRESS', '198 SCO 1st Floor, Omaxe World Street, Sector 79 Faridabad 121002')); ?>" target="_blank" rel="noopener" class="footer-info-link" style="color: var(--color-text-muted); font-size: 0.95rem; transition: var(--transition-fast);">
                            <?php echo htmlspecialchars(env('CONTACT_ADDRESS', '198 SCO 1st Floor, Omaxe World Street, Sector 79 Faridabad 121002')); ?>
                        </a>
                    </li>
                    <li style="display: flex; gap: 0.75rem; align-items: flex-start; line-height: 1.5; margin-top: 0.5rem; color: var(--color-text-muted); font-size: 0.95rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--color-accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 4px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <div>
                            <strong style="color: var(--color-text-light); font-weight: 600;">Office Hours:</strong><br>
                            Mon - Sat: 9:00 AM - 6:00 PM<br>
                            Sunday: Closed
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Footer Links (Quick Links) -->
            <div class="footer-col links-col">
                <h4 class="footer-title">Quick Links</h4>
                <ul class="footer-links-list">
                    <li><a href="index.php#hero"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg> Home</a></li>
                    <li><a href="about.php"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg> About Us</a></li>
                    <li><a href="properties.php"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg> Properties</a></li>
                    <li><a href="index.php#testimonials"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg> Testimonials</a></li>
                    <li><a href="contact.php"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg> Contact Us</a></li>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Copyright Bar -->
        <div class="footer-bottom">
            <div class="container footer-bottom-container">
                <p class="copyright-text">&copy; <?php echo date("Y"); ?> <?php echo htmlspecialchars(env('APP_NAME', 'Prime Edge Realiity')); ?>. All rights reserved. | A website Designed & Developed By <a href="<?php echo htmlspecialchars(env('DEVELOPER_URL', 'https://mineib.com')); ?>" target="_blank" rel="noopener" style="color: var(--color-accent); font-weight: 600;"><?php echo htmlspecialchars(env('DEVELOPER_NAME', 'Mineib')); ?></a></p>
                <div class="footer-bottom-links">
                    <a href="privacy-policy.php">Privacy Policy</a>
                    <a href="terms.php">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#hero" class="back-to-top" id="back-to-top-btn" aria-label="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>
    </a>

    <!-- Global JavaScript File -->
    <script src="assets/js/main.js"></script>
    <script>
        // Initialize Lucide icons if loaded
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>
