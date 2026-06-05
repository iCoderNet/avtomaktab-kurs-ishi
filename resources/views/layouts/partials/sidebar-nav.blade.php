@php
    $user = auth()->user();
    $isAdmin = $user->hasRole('admin');
    $isInstructor = $user->hasRole('instructor');
    $isStudent = $user->hasRole('student');

    // Faol route tekshirish uchun helper
    $isActive = fn(string $routeName) => request()->routeIs($routeName . '*')
        ? 'bg-indigo-600 text-white'
        : 'text-slate-400 hover:bg-slate-800 hover:text-white';
@endphp

{{-- ADMIN NAVIGATSIYASI --}}
@if ($isAdmin)
    @php $section = 'Admin Panel'; @endphp
    <x-nav-section title="{{ $section }}">

        <x-nav-item href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="home">
            Bosh sahifa
        </x-nav-item>

        <x-nav-item href="{{ route('admin.students.index') }}" :active="request()->routeIs('admin.students*')" icon="users">
            O'quvchilar
        </x-nav-item>

        <x-nav-item href="{{ route('admin.instructors.index') }}" :active="request()->routeIs('admin.instructors*')" icon="academic-cap">
            Instruktorlar
        </x-nav-item>

        <x-nav-item href="{{ route('admin.groups.index') }}" :active="request()->routeIs('admin.groups*')" icon="view-boards">
            Guruhlar
        </x-nav-item>

        <x-nav-item href="{{ route('admin.vehicles.index') }}" :active="request()->routeIs('admin.vehicles*')" icon="truck">
            Avtomobillar
        </x-nav-item>

        <x-nav-item href="{{ route('admin.schedules.index') }}" :active="request()->routeIs('admin.schedules*')" icon="calendar">
            Dars jadvali
        </x-nav-item>

        <x-nav-item href="{{ route('admin.exams.index') }}" :active="request()->routeIs('admin.exams*')" icon="clipboard-check">
            Imtihonlar
        </x-nav-item>

        <x-nav-item href="{{ route('admin.payments.index') }}" :active="request()->routeIs('admin.payments*')" icon="currency-dollar">
            To'lovlar
        </x-nav-item>

        <x-nav-item href="{{ route('admin.certificates.index') }}" :active="request()->routeIs('admin.certificates*')" icon="badge-check">
            Sertifikatlar
        </x-nav-item>

        <x-nav-item href="{{ route('admin.reports.index') }}" :active="request()->routeIs('admin.reports*')" icon="chart-bar">
            Hisobotlar
        </x-nav-item>

        <x-nav-item href="{{ route('notifications.index') }}" :active="request()->routeIs('notifications*')" icon="bell">
            Bildirishnomalar
        </x-nav-item>

    </x-nav-section>
@endif

{{-- INSTRUKTOR NAVIGATSIYASI --}}
@if ($isInstructor)
    <x-nav-section title="Instruktor Panel">

        <x-nav-item href="{{ route('instructor.dashboard') }}" :active="request()->routeIs('instructor.dashboard')" icon="home">
            Bosh sahifa
        </x-nav-item>

        <x-nav-item href="{{ route('instructor.students.index') }}" :active="request()->routeIs('instructor.students*')" icon="users">
            Mening o'quvchilarim
        </x-nav-item>

        <x-nav-item href="{{ route('instructor.schedule.index') }}" :active="request()->routeIs('instructor.schedule*')" icon="calendar">
            Dars jadvali
        </x-nav-item>

        <x-nav-item href="{{ route('instructor.attendance.index') }}" :active="request()->routeIs('instructor.attendance*')" icon="clipboard-list">
            Davomat
        </x-nav-item>

        <x-nav-item href="{{ route('instructor.grades.index') }}" :active="request()->routeIs('instructor.grades*')" icon="star">
            Baholar
        </x-nav-item>

        <x-nav-item href="{{ route('notifications.index') }}" :active="request()->routeIs('notifications*')" icon="bell">
            Bildirishnomalar
        </x-nav-item>

    </x-nav-section>
@endif

{{-- O'QUVCHI NAVIGATSIYASI --}}
@if ($isStudent)
    <x-nav-section title="O'quvchi Panel">

        <x-nav-item href="{{ route('student.dashboard') }}" :active="request()->routeIs('student.dashboard')" icon="home">
            Bosh sahifa
        </x-nav-item>

        <x-nav-item href="{{ route('student.lessons.index') }}" :active="request()->routeIs('student.lessons*')" icon="calendar">
            Darslarim
        </x-nav-item>

        <x-nav-item href="{{ route('student.exams.index') }}" :active="request()->routeIs('student.exams*')" icon="clipboard-check">
            Imtihonlarim
        </x-nav-item>

        <x-nav-item href="{{ route('student.grades.index') }}" :active="request()->routeIs('student.grades*')" icon="star">
            Baholarim
        </x-nav-item>

        <x-nav-item href="{{ route('student.payments.index') }}" :active="request()->routeIs('student.payments*')" icon="currency-dollar">
            To'lovlar
        </x-nav-item>

        <x-nav-item href="{{ route('student.certificates.index') }}" :active="request()->routeIs('student.certificates*')" icon="badge-check">
            Sertifikatlar
        </x-nav-item>

        <x-nav-item href="{{ route('notifications.index') }}" :active="request()->routeIs('notifications*')" icon="bell">
            Bildirishnomalar
        </x-nav-item>

    </x-nav-section>
@endif
