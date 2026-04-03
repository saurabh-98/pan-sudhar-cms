<footer class="main-footer">
    <div class="container-custom footer-grid">

        <!-- BRAND -->
        <div class="footer-col text-center">
            <h3>🍴 Foodies</h3>
            <p>
                {{ $settings['footer_tagline'] ?? 'Your favorite food delivery platform. Fast, fresh and affordable meals delivered to your doorstep.' }}
            </p>
        </div>

        <!-- QUICK LINKS -->
        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul>
                @foreach($links['quick_links'] ?? [] as $link)
                    <li>
                        <a href="{{ \Illuminate\Support\Str::startsWith($link->url, ['http', 'https']) 
                            ? $link->url 
                            : route('page.show', $link->url) }}">
                            {{ $link->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- SUPPORT -->
        <div class="footer-col">
            <h4>Support</h4>
            <ul>
                @foreach($links['support'] ?? [] as $link)
                    <li>
                        <a href="{{ \Illuminate\Support\Str::startsWith($link->url, ['http', 'https']) 
                            ? $link->url 
                            : route('page.show', $link->url) }}">
                            {{ $link->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- CONTACT -->
        <div class="footer-col">
            <h4>Contact Us</h4>
            <p>Email: {{ $settings['contact_email'] ?? 'support@foodies.com' }}</p>
            <p>Phone: {{ $settings['contact_phone'] ?? '+91 9876543210' }}</p>
            <p>Location: {{ $settings['contact_address'] ?? 'Delhi, India' }}</p>
        </div>

    </div>

    <!-- SOCIAL + COPYRIGHT -->
    <div class="footer-bottom">

        <p>
            {{ $settings['footer_text'] ?? '© '.date('Y').' Foodies. All Rights Reserved.' }}
        </p>

        <div class="social-icons">
            @foreach($socials as $social)
                <a href="{{ $social->url }}" target="_blank">
                    <i class="fa {{ $social->icon }}"></i>
                </a>
            @endforeach
        </div>

    </div>
</footer>