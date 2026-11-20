
@extends('layouts.app')

@section('title', 'Daftar Mata Kuliah')

@section('content')

<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <div>
            <h1 class="card-title">Daftar Mata Kuliah</h1>
            <p class="card-subtitle">
                Jelajahi dan temukan mata kuliah yang tersedia di CeLOE LMS.
            </p>
        </div>

        @if(auth()->user()->isDosen())
            <a href="{{ route('courses.create') }}" class="btn btn-primary">
                + Tambah Mata Kuliah
            </a>
        @endif
    </div>

    <div class="card-body">
        <form action="{{ route('courses.index') }}" method="GET">

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">

                <div>
                    <label for="search" style="display: block; font-weight: 600; margin-bottom: 0.4rem;">
                        Cari Mata Kuliah
                    </label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        placeholder="Kode atau nama mata kuliah..."
                        value="{{ request('search') }}"
                    >
                </div>

                {{-- TODO 6: Filter berdasarkan semester --}}
                <div>
                    <label for="semester" style="display: block; font-weight: 600; margin-bottom: 0.4rem;">
                        Filter Semester
                    </label>
                    <select name="semester" id="semester" class="form-control">
                        <option value="">Semua Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester }}"
                                {{ request('semester') == $semester ? 'selected' : '' }}>
                                {{ $semester }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TODO 6: Pengurutan mata kuliah --}}
                <div>
                    <label for="sort" style="display: block; font-weight: 600; margin-bottom: 0.4rem;">
                        Urutkan
                    </label>
                    <select name="sort" id="sort" class="form-control">
                        <option value="nama_asc" {{ request('sort', 'nama_asc') == 'nama_asc' ? 'selected' : '' }}>
                            Nama (A-Z)
                        </option>
                        <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>
                            Nama (Z-A)
                        </option>
                        <option value="sks_desc" {{ request('sort') == 'sks_desc' ? 'selected' : '' }}>
                            SKS Terbanyak
                        </option>
                        <option value="sks_asc" {{ request('sort') == 'sks_asc' ? 'selected' : '' }}>
                            SKS Paling Sedikit
                        </option>
                    </select>
                </div>

            </div>

            <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                <button type="submit" class="btn btn-primary">
                    Terapkan Filter
                </button>

                <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info" style="margin-bottom: 1rem;">
        {{ session('info') }}
    </div>
@endif

<div style="margin-bottom: 1rem;">
    <h2 style="font-size: 1.1rem; font-weight: 700;">
        Mata Kuliah Tersedia
    </h2>
    <p style="font-size: 0.875rem; color: var(--text-muted);">
        Menampilkan {{ $courses->count() }} dari {{ $courses->total() }} mata kuliah.
    </p>
</div>

@if($courses->isEmpty())

    <div class="card">
        <div class="card-body empty-state" style="padding: 3rem 1.5rem;">
            <h3> Mata Kuliah Tidak Ditemukan </h3>
            <p style="color: var(--text-muted);">
                Tidak ada mata kuliah yang sesuai dengan pencarian atau filter.
            </p>
        </div>
    </div>

@else

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem;">

        @foreach($courses as $course)

            <div class="card">
                <div class="card-body">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span class="code-pill">
                            {{ $course->kode_mk }}
                        </span>

                        <span style="font-size: 0.8rem; color: var(--text-muted);">
                            {{ $course->sks }} SKS
                        </span>
                    </div>

                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">
                        {{ $course->nama_mk }}
                    </h3>

                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                        Semester: {{ $course->semester }}
                    </p>

                    <p style="font-size: 0.875rem; margin-bottom: 1rem;">
                        Dosen: {{ $course->dosen->name ?? '-' }}
                    </p>

                    <div style="display: flex; gap: 1rem; flex-wrap: wrap; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">
                        <span>{{ $course->students_count }} Mahasiswa</span>
                        <span>{{ $course->materials_count }} Materi</span>
                        <span>{{ $course->assignments_count }} Tugas</span>
                    </div>

                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary btn-sm">
                        Lihat Detail
                    </a>

                </div>
            </div>

        @endforeach

    </div>

    <div style="margin-top: 1.5rem;">
        {{ $courses->links() }}
    </div>

@endif

@endsection
