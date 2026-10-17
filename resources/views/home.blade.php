<x-layouts.public :title="__('Home')" :profile="$profile" :description="$profile?->publicText('introduction') ?: null" :canonical="\App\Support\PublicUrl::route('home')">
    <div class="site-container home-flow">
        <section class="home-hero" aria-labelledby="home-title">
            <div class="home-hero-grid">
                <div class="home-hero-copy">
                    <p class="home-role">{{ $profile?->publicText('title') ?: __('Full-Stack Developer | Backend-Focused') }}</p>
                    <h1 id="home-title" class="home-title">{{ $profile?->publicText('full_name') ?: (app()->getLocale() === 'fa' ? __('Portfolio') : 'Amir Abbas Afshar') }}<span aria-hidden="true">.</span></h1>
                    <p class="home-specialization">{{ $profile?->publicText('specialization') ?: 'PHP & Laravel' }}</p>
                    <p class="mt-4 max-w-xl text-base leading-8 text-cyan-950/90 sm:text-lg">
                        @if($profile?->publicText('introduction')){{ $profile->publicText('introduction') }}@else<span class="home-placeholder">{{ __('Placeholder introduction — add one in Profile settings.') }}</span>@endif
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a class="button-primary" href="{{ \App\Support\PublicUrl::route('projects.index') }}">{{ __('View projects') }} <span aria-hidden="true">↗</span></a>
                        <a class="button-secondary home-button-dark" href="#contact">{{ __('Get in touch') }}</a>
                    </div>
                    @if($resumes->isNotEmpty())
                        <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 text-sm font-medium text-cyan-950" aria-label="{{ __('Resumes') }}">
                            @foreach($resumes as $resume)
                                <a class="underline decoration-cyan-950/30 underline-offset-4 hover:decoration-cyan-950" href="{{ route('resume.download', $resume->language) }}">{{ __('Download resume') }} <span aria-hidden="true">↓</span></a>
                            @endforeach
                        </div>
                    @endif
                    @if($socialLinks->isNotEmpty())
                        <ul class="mt-5 flex flex-wrap gap-x-4 gap-y-2 text-sm font-medium text-cyan-950" aria-label="{{ __('Social links') }}">
                            @foreach($socialLinks as $link)
                                @if(preg_match('/^https?:\/\//i', $link->url))
                                    <li><a class="underline decoration-cyan-950/30 underline-offset-4 hover:decoration-cyan-950" href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">{{ $link->publicText('label') }} <span aria-hidden="true">↗</span></a></li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="home-hero-art" aria-hidden="true">
                    <img src="{{ asset('images/illustrations/hero-laptop.webp') }}" class="section-illustration hero-illustration" alt="" width="1024" height="683" fetchpriority="high" decoding="async">
                </div>
            </div>
        </section>

        <section id="projects" class="home-section" aria-labelledby="projects-title">
            <div class="home-section-heading">
                <div>
                    <p class="home-kicker"><span></span>{{ __('Selected work') }}</p>
                    <h2 id="projects-title">{{ __('Projects') }}</h2>
                    <p>{{ __('A few selected projects') }}</p>
                </div>
                <a class="button-secondary" href="{{ \App\Support\PublicUrl::route('projects.index') }}">{{ __('All projects') }} <span aria-hidden="true">{{ app()->getLocale() === 'fa' ? '←' : '→' }}</span></a>
            </div>
            @if($featuredProjects->isEmpty())
                <p class="home-empty">{{ __('No featured projects have been published yet.') }}</p>
            @else
                <div class="grid gap-5 lg:grid-cols-2">@foreach($featuredProjects as $project)<x-project-card :project="$project" variant="featured" />@endforeach</div>
            @endif
        </section>

        <section id="skills" class="home-band home-skills" aria-labelledby="skills-title">
            <div class="skills-art" aria-hidden="true">
                <img src="{{ asset('images/illustrations/skills-stack.webp') }}" class="section-illustration skills-illustration" alt="" width="1024" height="936" loading="lazy" decoding="async">
            </div>
            <div class="skills-content">
                <p class="home-kicker"><span></span>{{ __('Skills') }}</p>
                <h2 id="skills-title">{{ __('Skills and technologies') }}</h2>
                <p class="home-band-intro">{{ __('Technical areas and tools') }}</p>
                @if($skillCategories->isEmpty())
                    <p class="home-empty home-empty-dark">{{ __('No visible skills have been added yet.') }}</p>
                @else
                    <div class="skill-groups">
                        @foreach($skillCategories as $category)
                            <div class="skill-group">
                                <h3>{{ $category->publicText('name') }}</h3>
                                <ul>
                                    @foreach($category->skills as $skill)
                                        <li><span>{{ $skill->publicText('name') }}</span><span class="skill-level">{{ __(ucfirst($skill->proficiency)) }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section id="about" class="home-band home-about" aria-labelledby="about-title">
            <div class="about-art" aria-hidden="true">
                <img src="{{ asset('images/illustrations/about-code.webp') }}" class="section-illustration about-illustration" alt="" width="1024" height="683" loading="lazy" decoding="async">
            </div>
            <div class="about-content">
                <p class="home-kicker"><span></span>{{ __('About') }}</p>
                <h2 id="about-title">{{ __('A little about me') }}</h2>
                <div class="mt-4 space-y-3 text-sm leading-7 text-paper/80 sm:text-base">
                    @if($profile?->publicText('approach'))<p class="whitespace-pre-line">{{ $profile->publicText('approach') }}</p>@endif
                    @if($profile?->publicText('background'))<p class="whitespace-pre-line">{{ $profile->publicText('background') }}</p>@endif
                    @if($profile?->publicText('expertise'))<p><strong class="text-paper">{{ __('Expertise') }}:</strong> {{ $profile->publicText('expertise') }}</p>@endif
                    @if($profile?->publicText('interests'))<p><strong class="text-paper">{{ __('Interests') }}:</strong> {{ $profile->publicText('interests') }}</p>@endif
                    @unless($profile?->publicText('approach') || $profile?->publicText('background') || $profile?->publicText('expertise') || $profile?->publicText('interests'))
                        <p class="home-placeholder">{{ __('Profile details have not been added yet.') }}</p>
                    @endunless
                </div>
            </div>
        </section>

        <section id="experience" class="home-band home-experience" aria-labelledby="experience-title">
            <div class="home-section-heading home-section-heading-light">
                <div>
                    <p class="home-kicker"><span></span>{{ __('Experience') }}</p>
                    <h2 id="experience-title">{{ __('Work experience') }}</h2>
                    <p>{{ __('Professional experience') }}</p>
                </div>
            </div>
            <div class="experience-columns">
                <div class="experience-list">
                    @forelse($workExperiences as $experience)
                        <article class="experience-entry">
                            <div class="experience-marker" aria-hidden="true">{{ $loop->first ? '▦' : '●' }}</div>
                            <div>
                                <p class="text-sm font-semibold text-white/80">{{ $experience->start_date->locale(app()->getLocale())->isoFormat('MMM YYYY') }}@if($experience->is_current) – {{ __('Present') }} @elseif($experience->end_date) – {{ $experience->end_date->locale(app()->getLocale())->isoFormat('MMM YYYY') }} @endif</p>
                                <h3>{{ $experience->publicText('job_title') }}</h3>
                                <p class="font-medium text-white/80">{{ $experience->publicText('company') }}@if($experience->publicText('employment_type')) · {{ $experience->publicText('employment_type') }}@endif @if($experience->publicText('location')) · {{ $experience->publicText('location') }}@endif</p>
                                @foreach(['description', 'responsibilities', 'achievements'] as $field)
                                    @if($experience->publicText($field))<p class="mt-3 whitespace-pre-line text-sm leading-6 text-white/75">{{ $experience->publicText($field) }}</p>@endif
                                @endforeach
                            </div>
                        </article>
                    @empty
                        <p class="home-empty home-empty-pink">{{ __('No work experience is listed yet.') }}</p>
                    @endforelse
                </div>
                <div class="education-list">
                    <h3 class="mb-4 text-lg font-bold text-white">{{ __('Education') }}</h3>
                    @forelse($educations as $education)
                        <article class="education-entry">
                            <p class="text-sm text-white/75">@if($education->start_date){{ $education->start_date->locale(app()->getLocale())->isoFormat('YYYY') }}@if($education->end_date) – {{ $education->end_date->locale(app()->getLocale())->isoFormat('YYYY') }}@endif @endif</p>
                            <h4>{{ $education->publicText('institution') }}</h4>
                            @if($education->publicText('degree') || $education->publicText('field_of_study'))<p>{{ collect([$education->publicText('degree'), $education->publicText('field_of_study')])->filter()->join(' · ') }}</p>@endif
                            @if($education->publicText('description'))<p class="mt-2 text-sm leading-6 text-white/75">{{ $education->publicText('description') }}</p>@endif
                        </article>
                    @empty
                        <p class="text-sm text-white/75">{{ __('No education is listed yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="articles" class="home-section" aria-labelledby="articles-title">
            <div class="home-section-heading">
                <div>
                    <p class="home-kicker"><span></span>{{ __('Writing') }}</p>
                    <h2 id="articles-title">{{ __('Latest articles') }}</h2>
                    <p>{{ __('Writing and insights') }}</p>
                </div>
                <a class="button-secondary" href="{{ \App\Support\PublicUrl::route('blog.index') }}">{{ __('All articles') }} <span aria-hidden="true">{{ app()->getLocale() === 'fa' ? '←' : '→' }}</span></a>
            </div>
            @if($latestPosts->isEmpty())
                <p class="home-empty">{{ __('No articles have been published yet.') }}</p>
            @else
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">@foreach($latestPosts as $post)<x-article-card :post="$post" variant="featured" />@endforeach</div>
            @endif
        </section>

        <section id="contact" class="home-band home-contact" aria-labelledby="contact-title">
            <div class="contact-art" aria-hidden="true">
                <img src="{{ asset('images/illustrations/contact-plane.webp') }}" class="section-illustration contact-illustration" alt="" width="1024" height="683" loading="lazy" decoding="async">
            </div>
            <div class="contact-content">
                <p class="home-kicker"><span></span>{{ __('Contact') }}</p>
                <h2 id="contact-title">{{ __('Let’s connect') }}</h2>
                <p class="mt-3 max-w-2xl leading-7 text-cyan-950/90">{{ __('Have a question or a project in mind? Send a message.') }}</p>
                @if($profile?->contact_email)<p class="mt-3 font-semibold text-cyan-950">{{ __('Or email') }} <a class="underline underline-offset-4" href="mailto:{{ $profile->contact_email }}">{{ $profile->contact_email }}</a></p>@endif
                @if($socialLinks->isNotEmpty())
                    <ul class="mt-5 flex flex-wrap gap-2" aria-label="{{ __('Social links') }}">
                        @foreach($socialLinks as $link)
                            @if(preg_match('/^https?:\/\//i', $link->url))
                                <li><a class="social-chip" href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">{{ $link->publicText('label') }} <span aria-hidden="true">↗</span></a></li>
                            @endif
                        @endforeach
                    </ul>
                @endif
                @if(session('contact_success'))<p class="mt-5 rounded-xl border border-cyan-950/20 bg-white/50 p-4 font-medium text-cyan-950" role="status">{{ session('contact_success') }}</p>@endif
                @if(session('contact_error'))<p class="mt-5 rounded-xl border border-red-900/20 bg-red-50/70 p-4 font-medium text-red-900" role="alert">{{ session('contact_error') }}</p>@endif
                @if($errors->any())<div class="mt-5 rounded-xl border border-red-900/20 bg-red-50/70 p-4 text-red-900" role="alert"><p>{{ __('Please correct the following:') }}</p><ul class="mt-2 list-disc ps-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                <form method="POST" action="{{ \App\Support\PublicUrl::route('contact.store') }}" class="contact-form">
                    @csrf
                    <div class="sr-only" aria-hidden="true"><label for="website">{{ __('Website') }}</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label class="contact-label" for="contact-name">{{ __('Name') }}</label><input class="contact-input" id="contact-name" name="name" type="text" maxlength="120" autocomplete="name" value="{{ old('name') }}" required aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"></div>
                        <div><label class="contact-label" for="contact-email">{{ __('Email') }}</label><input class="contact-input" id="contact-email" name="email" type="email" maxlength="254" autocomplete="email" value="{{ old('email') }}" required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"></div>
                    </div>
                    <div><label class="contact-label" for="contact-subject">{{ __('Subject') }}</label><input class="contact-input" id="contact-subject" name="subject" type="text" maxlength="200" value="{{ old('subject') }}" required aria-invalid="{{ $errors->has('subject') ? 'true' : 'false' }}"></div>
                    <div><label class="contact-label" for="contact-message">{{ __('Message') }}</label><textarea class="contact-input min-h-32" id="contact-message" name="message" maxlength="5000" required aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}">{{ old('message') }}</textarea></div>
                    <button class="button-primary" type="submit">{{ __('Send message') }} <span aria-hidden="true">↗</span></button>
                </form>
            </div>
        </section>
    </div>
</x-layouts.public>
