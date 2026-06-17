<?php
/**
 * Contact/Investment Inquiry Component for Prime Edge Realiity
 */
?>
<section class="contact-section" id="contact">
    <!-- Huge background outline text -->
    <div class="outline-text contact-bg-text">CONNECT</div>

    <div class="container">
        <div class="contact-grid grid-2">
            <!-- Left Side: General Info & Addresses -->
            <div class="contact-info-pane">
                <span class="section-tagline">GET IN TOUCH</span>
                <h2 class="section-title">Ready To Find Your Edge?</h2>
                <p class="contact-desc">
                    Connect with our luxury estate brokers and smart investment analysts today. Fill out the form, or reach us directly at our regional offices.
                </p>

                <!-- Contact items -->
                <div class="contact-items-list">
                    <!-- Phone -->
                    <div class="contact-item-box">
                        <div class="contact-icon">
                            <i data-lucide="phone-call"></i>
                        </div>
                        <div class="contact-text">
                            <span class="contact-label">Call Us Directly</span>
                            <a href="tel:<?php echo htmlspecialchars(env('CONTACT_PHONE_RAW', '+919310104249')); ?>" class="contact-link"><?php echo htmlspecialchars(env('CONTACT_PHONE', '+91 93101 04249')); ?></a>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="contact-item-box">
                        <div class="contact-icon">
                            <i data-lucide="mail"></i>
                        </div>
                        <div class="contact-text">
                            <span class="contact-label">Email Our Consultants</span>
                            <a href="mailto:<?php echo htmlspecialchars(env('CONTACT_EMAIL', 'invest@peprealty.com')); ?>" class="contact-link"><?php echo htmlspecialchars(env('CONTACT_EMAIL', 'invest@peprealty.com')); ?></a>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="contact-item-box">
                        <div class="contact-icon">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <div class="contact-text">
                            <span class="contact-label">Headquarters</span>
                            <address class="contact-address"><?php echo htmlspecialchars(env('CONTACT_ADDRESS', '198 SCO 1st Floor, Omaxe World Street, Sector 79 Faridabad 121002')); ?></address>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Premium Contact / Inquiry Form -->
            <div class="contact-form-pane">
                <div class="contact-form-card">
                    <h3 class="form-title">Investment Inquiry</h3>
                    <p class="form-subtitle">Submit your interest and our agent will reach out in 24 hours.</p>
                    
                    <form class="inquiry-form" id="property-inquiry" onsubmit="event.preventDefault(); alert('Your inquiry has been successfully submitted. One of our specialist investment advisors will call you shortly.'); this.reset();">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="inquiry-name" class="form-label">Full Name</label>
                            <input type="text" id="inquiry-name" placeholder="John Doe" required class="form-input">
                        </div>

                        <!-- Email & Phone row -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="inquiry-email" class="form-label">Email Address</label>
                                <input type="email" id="inquiry-email" placeholder="john@example.com" required class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="inquiry-phone" class="form-label">Phone Number</label>
                                <input type="tel" id="inquiry-phone" placeholder="+1 (555) 000-0000" required class="form-input">
                            </div>
                        </div>

                        <!-- Property Dropdown -->
                        <div class="form-group">
                            <label for="inquiry-property" class="form-label">Property Interest</label>
                            <div class="select-wrapper">
                                <select id="inquiry-property" class="form-select">
                                    <option value="Eco-Solar Villa">Eco-Solar Villa (Beverly Hills, CA)</option>
                                    <option value="Cubic Glass Manor">Cubic Glass Manor (Malibu, CA)</option>
                                    <option value="Contemporary Mansion">Contemporary Mansion (Miami, FL)</option>
                                    <option value="General Investment">General Smart Investment Enquiry</option>
                                    <option value="Sell Property">Listing my property for sale</option>
                                </select>
                                <i data-lucide="chevron-down" class="select-arrow"></i>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="form-group">
                            <label for="inquiry-message" class="form-label">Message / Budget</label>
                            <textarea id="inquiry-message" rows="4" placeholder="Tell us about your requirements, timeline, or investment budget..." required class="form-textarea"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-full form-submit-btn" style="width: 100%;">
                            Submit Inquiry <i data-lucide="send"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
