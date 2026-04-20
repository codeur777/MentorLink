@extends('layouts.app')
@section('title', 'Laisser un avis')

@section('content')

<div class="mb-6">
    <a href="{{ route('sessions.index') }}" class="text-vista text-sm hover:text-oxford transition">
        <i class="fa-solid fa-arrow-left mr-1"></i> Mes sessions
    </a>
</div>

@if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <ul class="text-red-600 text-sm space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="max-w-lg">

    {{-- Résumé session --}}
    <div class="bg-oxford rounded-2xl p-5 mb-4 flex items-center gap-4">
        <div class="w-12 h-12 bg-orange rounded-xl flex flex-col items-center justify-center flex-shrink-0">
            <span class="text-oxford text-xs font-bold">{{ $session->date->format('d') }}</span>
            <span class="text-oxford/70 text-xs">{{ $session->date->format('M') }}</span>
        </div>
        <div>
            <p class="text-white font-semibold">Session avec {{ $session->mentor->name }}</p>
            <p class="text-vista text-sm">{{ $session->start_time }} – {{ $session->end_time }}</p>
        </div>
    </div>

    {{-- Formulaire avis --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-5">Ton avis</p>

        <form method="POST" action="{{ route('reviews.store', $session) }}" class="flex flex-col gap-5">
            @csrf

            {{-- Note étoiles --}}
            <div>
                <label class="text-xs font-semibold text-oxford mb-3 block">Note</label>
                <div class="flex gap-3">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}"
                                   {{ old('rating') == $i ? 'checked' : '' }} required class="sr-only">
                            <span class="text-3xl transition hover:scale-110 block
                                {{ old('rating') >= $i ? 'text-orange' : 'text-gray-200' }}"
                                  onclick="updateStars({{ $i }})">★</span>
                        </label>
                    @endfor
                </div>
            </div>

            {{-- Commentaire --}}
            <div>
                <label class="text-xs font-semibold text-oxford mb-1 block">Commentaire (optionnel)</label>
                <textarea name="comment" rows="4" maxlength="1000"
                          placeholder="Partage ton expérience avec ce mentor..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-orange transition resize-none">{{ old('comment') }}</textarea>
            </div>

            <button type="submit"
                    class="bg-orange text-white font-semibold py-3 rounded-xl hover:opacity-90 transition text-sm">
                Soumettre l'avis
            </button>
        </form>
    </div>
</div>

<script>
    function updateStars(rating) {
        const stars = document.querySelectorAll('[onclick^="updateStars"]');
        stars.forEach((s, i) => {
            s.classList.toggle('text-orange', i < rating);
            s.classList.toggle('text-gray-200', i >= rating);
        });
    }
</script>

@endsection
