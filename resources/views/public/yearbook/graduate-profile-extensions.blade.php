{{-- Section 12: Professional Exposure + Personal Elements --}}
@if($graduate->internships || $graduate->certifications_training)
    <section class="section-card scroll-reveal">
        <h2>Professional Exposure</h2>
        @if($graduate->internships)
            <div class="profile-extension-block">
                <h3>Internships &amp; Selected Work Experience</h3>
                <p>{!! nl2br(e(is_array($graduate->internships) ? implode("\n", $graduate->internships) : $graduate->internships)) !!}</p>
            </div>
        @endif
        @if($graduate->certifications_training)
            <div class="profile-extension-block">
                <h3>Certifications &amp; Training</h3>
                <p>{!! nl2br(e($graduate->certifications_training)) !!}</p>
            </div>
        @endif
    </section>
@endif

@if($graduate->professional_interests || $graduate->future_plans || !empty($graduate->approved_links))
    <section class="section-card scroll-reveal">
        <h2>Personal Elements</h2>
        @if($graduate->professional_interests)
            <div class="profile-extension-block">
                <h3>Professional Interests</h3>
                <p>{!! nl2br(e($graduate->professional_interests)) !!}</p>
            </div>
        @endif
        @if($graduate->future_plans)
            <div class="profile-extension-block">
                <h3>Future Plans</h3>
                <p>{!! nl2br(e($graduate->future_plans)) !!}</p>
            </div>
        @endif
        @if(!empty($graduate->approved_links))
            <div class="profile-extension-block">
                <h3>Approved Links</h3>
                <div class="approved-links-list">
                    @foreach($graduate->approved_links as $link)
                        @php($label = parse_url($link, PHP_URL_HOST) ?: 'Approved link')
                        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $label }} <span aria-hidden="true">↗</span></a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endif

<style>
.profile-extension-block + .profile-extension-block{margin-top:24px;padding-top:22px;border-top:1px solid var(--profile-line,#d8e3ef)}
.profile-extension-block h3{margin:0 0 8px;color:var(--profile-ink,#002a5c);font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
.profile-extension-block p{margin:0;color:#465970;font-size:.98rem;line-height:1.9}
.approved-links-list{display:flex;flex-wrap:wrap;gap:10px}
.approved-links-list a{display:inline-flex;align-items:center;gap:7px;padding:10px 13px;border:1px solid var(--profile-line,#d8e3ef);border-radius:12px;background:var(--profile-paper,#f7fbff);color:var(--profile-ink,#002a5c);font-size:.86rem;font-weight:700;text-decoration:none;transition:transform .25s ease,background .25s ease,color .25s ease,border-color .25s ease}
.approved-links-list a:hover{transform:translateY(-3px);background:var(--profile-ink,#002a5c);border-color:var(--profile-ink,#002a5c);color:#fff}
</style>
