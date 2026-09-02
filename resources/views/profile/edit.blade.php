<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div class="page-header-inner dashboard-heading">
                <div>
                    <p class="eyebrow">Account</p>
                    <h1>Profile Settings</h1>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="site-shell">
        <main class="page-content dashboard-wrap">
            <div class="profile-grid">

                {{-- Profile Information --}}
                <section class="form-section">
                    @include('profile.partials.update-profile-information-form')
                </section>

                {{-- Update Password --}}
                <section class="form-section">
                    @include('profile.partials.update-password-form')
                </section>

            </div>
        </main>
    </div>

</x-app-layout>

<style>
    .profile-grid {
        display: grid;
        gap: 24px;
        grid-template-columns: minmax(0, 1fr);
    }

    .profile-grid .form-section {
        min-width: 0;
    }

    .profile-grid .form-section>section {
        min-width: 0;
    }

    .profile-danger {
        border-color: #e7b7a5;
    }

    .profile-danger h2 {
        color: #8b3a0d;
    }

    .profile-danger p {
        color: var(--ink-soft);
    }

    .profile-danger .danger-heading {
        border-bottom-color: #ead8cb;
    }

    @media (max-width: 600px) {
        .profile-grid {
            gap: 18px;
        }
    }
</style>