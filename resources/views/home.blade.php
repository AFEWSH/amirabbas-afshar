<x-layouts.public title="Home" :profile="$profile" :description="$profile?->introduction ?: null" :canonical="route('home')">
    <section class="site-container grid gap-12 py-24 sm:py-32 lg:grid-cols-[1.4fr_.6fr] lg:items-end">
        <div>
            <p class="mb-6 text-sm font-semibold uppercase tracking-[.22em] text-accent">{{ $profile?->title ?: 'Full-Stack Developer | Backend-Focused' }}</p>
            <h1 class="max-w-4xl text-5xl font-semibold leading-[1.07] tracking-tight sm:text-7xl">{{ $profile?->full_name ?: 'Amir Abbas Afshar' }}<span class="text-accent">.</span></h1>
            <p class="mt-7 max-w-xl text-xl text-muted">{{ $profile?->specialization ?: 'PHP & Laravel' }}. @if($profile?->introduction) {{ $profile->introduction }} @else <span class="placeholder-label">Placeholder introduction — add one in Profile settings.</span> @endif</p>
            <div class="mt-9 flex flex-wrap gap-3">
                <a class="button-primary" href="{{ route('projects.index') }}">View projects <span aria-hidden="true">↗</span></a>
                <a class="button-secondary" href="#contact">Get in touch</a>
            </div>
            @if($resumes->isNotEmpty())
                <div class="mt-6 flex flex-wrap gap-x-5 gap-y-2 text-sm text-muted" aria-label="Resumes">
                    @foreach($resumes as $resume)
                        <a class="text-accent hover:underline" href="{{ route('resume.download', $resume->language) }}">Download resume ({{ strtoupper($resume->language) }}) ↓</a>
                    @endforeach
                </div>
            @endif
            @if($socialLinks->isNotEmpty())
                <ul class="mt-7 flex flex-wrap gap-x-5 gap-y-2 text-sm">
                    @foreach($socialLinks as $link)
                        @if(preg_match('/^https?:\/\//i', $link->url))
                            <li><a class="text-accent hover:underline" href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">{{ $link->label }} <span aria-hidden="true">↗</span></a></li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="border-s border-accent/60 ps-5 text-sm leading-7 text-muted">
            @if($profile?->approach)<p class="whitespace-pre-line">{{ $profile->approach }}</p>@else<p class="placeholder-label">Placeholder approach — add one in Profile settings.</p>@endif
        </div>
    </section>

    <section id="about" class="section-block border-t border-line">
        <div class="site-container grid gap-10 lg:grid-cols-[1fr_1.5fr]">
            <x-section-heading eyebrow="01 / About" title="A little about me" />
            <div class="space-y-5 text-lg leading-8 text-muted">
                @if($profile?->background)<p class="whitespace-pre-line">{{ $profile->background }}</p>@endif
                @if($profile?->expertise)<p class="whitespace-pre-line"><span class="font-semibold text-paper">Expertise</span><br>{{ $profile->expertise }}</p>@endif
                @if($profile?->interests)<p class="whitespace-pre-line"><span class="font-semibold text-paper">Interests</span><br>{{ $profile->interests }}</p>@endif
                @unless($profile?->background || $profile?->expertise || $profile?->interests)<p class="placeholder-label">Profile details have not been added yet.</p>@endunless
            </div>
        </div>
    </section>

    <section id="skills" class="section-block border-t border-line">
        <div class="site-container">
            <x-section-heading eyebrow="02 / Skills" title="What I work with" />
            @if($skillCategories->isEmpty())
                <div class="rounded-2xl border border-dashed border-line bg-surface p-8 text-muted">No visible skills have been added yet.</div>
            @else
                <div class="grid gap-5 md:grid-cols-2">
                    @foreach($skillCategories as $category)
                        <div class="rounded-2xl border border-line bg-surface p-7">
                            <h3 class="text-xl font-semibold text-paper">{{ $category->name }}</h3>
                            <ul class="mt-5 space-y-3">
                                @foreach($category->skills as $skill)
                                    <li class="flex items-center justify-between gap-4 border-t border-line pt-3"><span>{{ $skill->name }}</span><span class="text-sm text-muted">{{ ucfirst($skill->proficiency) }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section id="experience" class="section-block border-t border-line">
        <div class="site-container grid gap-12 lg:grid-cols-2">
            <div>
                <x-section-heading eyebrow="03 / Experience" title="Work experience" />
                @forelse($workExperiences as $experience)
                    <article class="mb-8 border-s border-line ps-5">
                        <p class="text-sm text-accent">{{ $experience->start_date->format('M Y') }}@if($experience->is_current) – Present @elseif($experience->end_date) – {{ $experience->end_date->format('M Y') }} @endif</p>
                        <h3 class="mt-2 text-xl font-semibold text-paper">{{ $experience->job_title }}</h3>
                        <p class="text-muted">{{ $experience->company }}@if($experience->employment_type) · {{ $experience->employment_type }}@endif @if($experience->location) · {{ $experience->location }}@endif</p>
                        @foreach(['description', 'responsibilities', 'achievements'] as $field)
                            @if($experience->$field)<p class="mt-3 whitespace-pre-line leading-7 text-muted">{{ $experience->$field }}</p>@endif
                        @endforeach
                    </article>
                @empty
                    <p class="border-s border-line ps-5 text-muted">No work experience is listed yet.</p>
                @endforelse
            </div>
            <div>
                <x-section-heading eyebrow="04 / Education" title="Education" />
                @forelse($educations as $education)
                    <article class="mb-8 border-s border-line ps-5">
                        @if($education->start_date)<p class="text-sm text-accent">{{ $education->start_date->format('Y') }}@if($education->end_date) – {{ $education->end_date->format('Y') }}@endif</p>@endif
                        <h3 class="mt-2 text-xl font-semibold text-paper">{{ $education->institution }}</h3>
                        @if($education->degree || $education->field_of_study)<p class="text-muted">{{ collect([$education->degree, $education->field_of_study])->filter()->join(' · ') }}</p>@endif
                        @if($education->description)<p class="mt-3 whitespace-pre-line leading-7 text-muted">{{ $education->description }}</p>@endif
                    </article>
                @empty
                    <p class="border-s border-line ps-5 text-muted">No education is listed yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section-block border-t border-line">
        <div class="site-container">
            <x-section-heading eyebrow="05 / Selected work" title="Projects" />
            @if($featuredProjects->isEmpty())<p class="rounded-2xl border border-dashed border-line bg-surface p-8 text-muted">No featured projects have been published yet.</p>
            @else<div class="grid gap-5 md:grid-cols-2">@foreach($featuredProjects as $project)<x-project-card :project="$project" />@endforeach</div>@endif
            <a class="mt-7 inline-block text-sm font-semibold text-accent hover:underline" href="{{ route('projects.index') }}">All projects →</a>
        </div>
    </section>

    <section class="section-block border-t border-line">
        <div class="site-container">
            <x-section-heading eyebrow="06 / Writing" title="Latest articles" />
            @if($latestPosts->isEmpty())<p class="rounded-2xl border border-dashed border-line bg-surface p-8 text-muted">No articles have been published yet.</p>
            @else<div class="grid gap-5 md:grid-cols-2">@foreach($latestPosts as $post)<x-article-card :post="$post" />@endforeach</div>@endif
            <a class="mt-7 inline-block text-sm font-semibold text-accent hover:underline" href="{{ route('blog.index') }}">All articles →</a>
        </div>
    </section>

    <section id="contact" class="section-block border-t border-line">
        <div class="site-container">
            <x-section-heading eyebrow="07 / Contact" title="Let's connect" description="Send a message using the form below." />
            @if($profile?->contact_email)<p class="mb-7 text-muted">Or email <a class="text-accent hover:underline" href="mailto:{{ $profile->contact_email }}">{{ $profile->contact_email }}</a></p>@endif
            @if(session('contact_success'))<p class="mb-7 rounded-lg border border-accent/50 bg-surface p-4 text-accent" role="status">{{ session('contact_success') }}</p>@endif
            @if(session('contact_error'))<p class="mb-7 rounded-lg border border-red-400/50 bg-surface p-4 text-red-300" role="alert">{{ session('contact_error') }}</p>@endif
            @if($errors->any())<div class="mb-7 rounded-lg border border-red-400/50 bg-surface p-4 text-red-300" role="alert"><p>Please correct the following:</p><ul class="mt-2 list-disc ps-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form method="POST" action="{{ route('contact.store') }}" class="max-w-2xl space-y-5">
                @csrf
                <div class="sr-only" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div><label class="mb-2 block text-sm font-medium" for="contact-name">Name</label><input class="form-input" id="contact-name" name="name" type="text" maxlength="120" autocomplete="name" value="{{ old('name') }}" required aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"></div>
                    <div><label class="mb-2 block text-sm font-medium" for="contact-email">Email</label><input class="form-input" id="contact-email" name="email" type="email" maxlength="254" autocomplete="email" value="{{ old('email') }}" required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"></div>
                </div>
                <div><label class="mb-2 block text-sm font-medium" for="contact-subject">Subject</label><input class="form-input" id="contact-subject" name="subject" type="text" maxlength="200" value="{{ old('subject') }}" required aria-invalid="{{ $errors->has('subject') ? 'true' : 'false' }}"></div>
                <div><label class="mb-2 block text-sm font-medium" for="contact-message">Message</label><textarea class="form-input min-h-40" id="contact-message" name="message" maxlength="5000" required aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}">{{ old('message') }}</textarea></div>
                <button class="button-primary" type="submit">Send message <span aria-hidden="true">↗</span></button>
            </form>
        </div>
    </section>
</x-layouts.public>
