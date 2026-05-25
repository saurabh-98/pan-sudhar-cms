<!-- =========================================================
| ADVANCED FOOTER
========================================================= -->

<footer class="sg-footer">

    <!-- GLOW EFFECTS -->
    <div class="sg-footer-glow sg-footer-glow-1"></div>
    <div class="sg-footer-glow sg-footer-glow-2"></div>

    <div class="container-custom">

        <!-- =====================================================
        | TOP FOOTER
        ===================================================== -->

        <div class="sg-footer-grid">

            <!-- =================================================
            | BRAND
            ================================================= -->

            <div class="sg-footer-col sg-footer-brand">

                <div class="sg-footer-logo">

                    <div class="sg-footer-logo-icon">

                        🪪

                    </div>

                    <div>

                        <h3>

                            {{ $settings['site_name'] ?? 'PAN & Aadhaar Suvidha Portal' }}

                        </h3>

                        <span>

                            PAN • Aadhaar • Online Services

                        </span>

                    </div>

                </div>

                <p class="sg-footer-desc">

                    {{ $settings['footer_tagline'] ?? 'Providing fast, secure, and trusted PAN Card, Aadhaar, correction, verification, and online documentation services across India.' }}

                </p>

                <!-- SOCIAL -->
                <div class="sg-footer-social">

                    @foreach($socials ?? [] as $social)

                        <a href="{{ $social->url }}"
                           target="_blank">

                            <i class="fa-brands {{ $social->icon }}"></i>

                        </a>

                    @endforeach

                </div>

            </div>

            <!-- =================================================
            | QUICK LINKS
            ================================================= -->

            <div class="sg-footer-col">

                <h4>

                    Quick Links

                </h4>

                <ul>

                    @foreach($links['quick_links'] ?? [] as $link)

                        <li>

                            <a href="{{ \Illuminate\Support\Str::startsWith($link->url, ['http','https']) ? $link->url : route('page.show', $link->url) }}">

                                <i class="fa-solid fa-angle-right"></i>

                                {{ $link->name }}

                            </a>

                        </li>

                    @endforeach

                </ul>

            </div>

            <!-- =================================================
            | SUPPORT
            ================================================= -->

            <div class="sg-footer-col">

                <h4>

                    Support

                </h4>

                <ul>

                    @foreach($links['support'] ?? [] as $link)

                        <li>

                            <a href="{{ \Illuminate\Support\Str::startsWith($link->url, ['http','https']) ? $link->url : route('page.show', $link->url) }}">

                                <i class="fa-solid fa-angle-right"></i>

                                {{ $link->name }}

                            </a>

                        </li>

                    @endforeach

                </ul>

            </div>

            <!-- =================================================
            | CONTACT
            ================================================= -->

            <div class="sg-footer-col sg-footer-contact">

                <h4>

                    Contact Us

                </h4>

                <div class="sg-contact-list">

                    <div class="sg-contact-item">

                        <div class="sg-contact-icon">

                            <i class="fa-solid fa-envelope"></i>

                        </div>

                        <div>

                            <span>Email</span>

                            <p>

                                {{ $settings['contact_email']
                                    ?? 'support@panaadhaarsuvidha.com' }}

                            </p>

                        </div>

                    </div>

                    <div class="sg-contact-item">

                        <div class="sg-contact-icon">

                            <i class="fa-solid fa-phone"></i>

                        </div>

                        <div>

                            <span>Phone</span>

                            <p>

                                {{ $settings['contact_phone']
                                    ?? '+91 9876543210' }}

                            </p>

                        </div>

                    </div>

                    <div class="sg-contact-item">

                        <div class="sg-contact-icon">

                            <i class="fa-solid fa-location-dot"></i>

                        </div>

                        <div>

                            <span>Location</span>

                            <p>

                                {{ $settings['contact_address']
                                    ?? 'Mithapur, Patna, Bihar, India' }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- =====================================================
        | FOOTER BOTTOM
        ===================================================== -->

        <div class="sg-footer-bottom">

            <p>

                {{ $settings['footer_text']
                    ?? '© '.date('Y').' PAN & Aadhaar Suvidha Portal. All Rights Reserved.' }}

            </p>

            <div class="sg-footer-bottom-links">

                <a href="{{ url('/privacy-policy') }}">

                    Privacy Policy

                </a>

                <a href="{{ url('/terms-conditions') }}">

                    Terms & Conditions

                </a>

            </div>

        </div>

    </div>

</footer>