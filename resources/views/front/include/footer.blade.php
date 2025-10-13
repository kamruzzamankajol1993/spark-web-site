<footer class="spark_footer_main">
    <div class="spark_container">
        <div class="row">

            <!-- Column 1: Contact Us -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="spark_footer_title">Contact Us</h5>

                <div class="spark_footer_contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $front_ins_email }}</span>
                </div>

                <div class="spark_footer_contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>{{ $front_ins_phone }}</span>
                </div>

                {{-- START: DYNAMIC SOCIAL LINKS SECTION --}}
                @if(isset($socialLinks) && $socialLinks->isNotEmpty())
                <div class="d-flex mb-3">
                    @foreach($socialLinks as $link)
                        @php
                            // Determine icon and class based on the link's title
                            $iconClass = 'fas fa-link'; // A default icon
                            $platformClass = '';
                            $titleLower = strtolower($link->title);

                            if (str_contains($titleLower, 'facebook')) {
                                $iconClass = 'fab fa-facebook-f';
                                $platformClass = 'facebook';
                            } elseif (str_contains($titleLower, 'youtube')) {
                                $iconClass = 'fab fa-youtube';
                                $platformClass = 'youtube';
                            } elseif (str_contains($titleLower, 'instagram')) {
                                $iconClass = 'fab fa-instagram';
                                $platformClass = 'instagram';
                            } elseif (str_contains($titleLower, 'whatsapp')) {
                                $iconClass = 'fab fa-whatsapp';
                                $platformClass = 'whatsapp';
                            } elseif (str_contains($titleLower, 'linkedin')) {
                                $iconClass = 'fab fa-linkedin-in';
                                $platformClass = 'linkedin';
                            } elseif (str_contains($titleLower, 'twitter')) {
                                $iconClass = 'fab fa-twitter';
                                $platformClass = 'twitter';
                            }
                        @endphp
                        <a href="{{ $link->link }}" class="spark_footer_social-link {{ $platformClass }}" target="_blank" rel="noopener noreferrer" title="{{ $link->title }}">
                            <i class="{{ $iconClass }}"></i>
                        </a>
                    @endforeach
                </div>
                @endif
                {{-- END: DYNAMIC SOCIAL LINKS SECTION --}}

                <div class="d-flex flex-wrap">
                    <a href="#" class="spark_footer_action-btn">
                        <i class="fas fa-map-marker-alt"></i> Our Location
                    </a>
                </div>
            </div>

            <!-- Column 2: Information -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="spark_footer_title">Information</h5>
                <ul class="spark_footer_link-list">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Brands</a></li>
                    <li><a href="#">Pickup Points</a></li>
                    <li><a href="#">Affiliation</a></li>
                    <li><a href="#">Useful Tools</a></li>
                    <li><a href="#">EMI Information</a></li>
                </ul>
            </div>

            <!-- Column 3: Policies -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="spark_footer_title">Policies</h5>
                  @if(isset($extraPages))
                    <ul class="spark_footer_link-list">
                        @if($extraPages->privacy_policy)
                            <li><a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a></li>
                        @endif
                        @if($extraPages->warranty_policy)
                            <li><a href="{{ route('page.show', 'warranty-policy') }}">Warranty Policy</a></li>
                        @endif
                        @if($extraPages->payment_term)
                            <li><a href="{{ route('page.show', 'payment-terms') }}">Payment Terms</a></li>
                        @endif
                        @if($extraPages->delivery_policy)
                            <li><a href="{{ route('page.show', 'delivery-policy') }}">Delivery Policy</a></li>
                        @endif
                        @if($extraPages->term_condition)
                            <li><a href="{{ route('page.show', 'terms-and-conditions') }}">Terms & Conditions</a></li>
                        @endif
                        @if($extraPages->refund_policy)
                            <li><a href="{{ route('page.show', 'refund-policy') }}">Refund and Return Policy</a></li>
                        @endif
                    </ul>
                @endif
            </div>

            <!-- Column 4: Affiliation & Payment -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="spark_footer_title">Affiliation</h5>
                <!-- BASIS Logo (Placeholder) -->
                <div class="mb-3">
                    <img src="{{ asset('public/front/assets/img/basis-299x105.png') }}" alt="BASIS Logo" class="spark_footer_affiliation-logo">
                </div>
            </div>

        </div>
    </div>

    <!-- Bottom Bar (Copyright and Payment Icons) -->
    <div class="spark_footer_bottom-bar">
        <div class="spark_container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-2 mb-md-0">
                Copyright &copy; 2025, {{$front_ins_name}}. All Rights Reserved.
            </p>

            <div class="d-flex align-items-center">
                <!-- Payment Logos (Simplified placeholders) -->
                <img src="{{ asset('public/front/assets/img/sscommerz_cache_optimize-70.webp') }}" alt="Visa" class="spark_footer_payment-logo">
            </div>
        </div>
    </div>
</footer>