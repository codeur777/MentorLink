@extends('layouts.admin')

@section('title', 'Signalements')
@section('subtitle', 'Gestion des signalements utilisateurs')

@section('content')

@if($reports->count() > 0)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-widest">#</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-widest">Signalant</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-widest">Signalé</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-widest">Session</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-widest">Motif</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-widest">Statut</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($reports as $report)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">#{{ $report->id }}</td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-vista/20 flex items-center justify-center text-xs font-bold text-oxford">
                                {{ strtoupper(substr($report->reporter->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-oxford font-medium">{{ $report->reporter->name }}</p>
                                <p class="text-gray-400 text-xs capitalize">{{ $report->reporter->role }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-orange/10 flex items-center justify-center text-xs font-bold text-orange">
                                {{ strtoupper(substr($report->reported->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-oxford font-medium">{{ $report->reported->name }}</p>
                                @if($report->reported->suspended)
                                    <span class="text-xs bg-red-100 text-red-500 px-2 py-0.5 rounded-full">Suspendu</span>
                                @else
                                    <p class="text-gray-400 text-xs capitalize">{{ $report->reported->role }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <p class="text-oxford text-xs font-medium">{{ $report->session->date->format('d/m/Y') }}</p>
                        <p class="text-gray-400 text-xs">{{ $report->session->start_time }} – {{ $report->session->end_time }}</p>
                    </td>

                    <td class="px-6 py-4 max-w-xs">
                        <p class="text-gray-600 text-xs line-clamp-2">{{ $report->reason }}</p>
                    </td>

                    <td class="px-6 py-4">
                        @if($report->status === 'open')
                            <span class="bg-orange/10 text-orange text-xs font-semibold px-3 py-1 rounded-full">Ouvert</span>
                        @else
                            <span class="bg-green-50 text-green-600 text-xs font-semibold px-3 py-1 rounded-full">Résolu</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            {{-- Résoudre --}}
                            @if($report->status === 'open')
                                <form method="POST" action="{{ route('admin.reports.resolve', $report) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="bg-oxford text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:opacity-80 transition">
                                        Résoudre
                                    </button>
                                </form>
                            @endif

                            {{-- Suspendre / lever --}}
                            @if(!$report->reported->isAdmin())
                                @if(!$report->reported->suspended)
                                    <form method="POST" action="{{ route('admin.users.suspend', $report->reported) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                onclick="return confirm('Suspendre {{ $report->reported->name }} ?')"
                                                class="bg-red-50 text-red-500 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                                            Suspendre
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.unsuspend', $report->reported) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="bg-green-50 text-green-600 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-green-100 transition">
                                            Lever
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $reports->links() }}</div>

@else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-shield-halved text-green-500 text-2xl"></i>
        </div>
        <p class="text-oxford font-bold text-lg mb-1">Aucun signalement</p>
        <p class="text-gray-400 text-sm">La plateforme est calme pour le moment.</p>
    </div>
@endif

@endsection
