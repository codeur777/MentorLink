<!DOCTYPE html>
<html>
<head>
    <title>Signalements - Admin MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Signalements</h1>

    <nav>
        <a href="{{ route('admin.dashboard') }}">← Dashboard admin</a>
    </nav>

    @if(session('success'))
        <div style="color:green; margin:10px 0;">{{ session('success') }}</div>
    @endif

    @if($reports->count() > 0)
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
            <thead style="background:#f0f0f0;">
                <tr>
                    <th>#</th>
                    <th>Signalant</th>
                    <th>Signale</th>
                    <th>Session</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr style="{{ $report->status === 'open' ? 'background:#fff3cd;' : '' }}">
                        <td>{{ $report->id }}</td>
                        <td>
                            {{ $report->reporter->name }}<br>
                            <small>({{ $report->reporter->role }})</small>
                        </td>
                        <td>
                            {{ $report->reported->name }}<br>
                            <small>({{ $report->reported->role }})</small>
                            @if($report->reported->suspended)
                                <br><span style="color:red; font-size:0.85em;">Suspendu</span>
                            @endif
                        </td>
                        <td>
                            {{ $report->session->date->format('d/m/Y') }}<br>
                            <small>{{ $report->session->start_time }} – {{ $report->session->end_time }}</small>
                        </td>
                        <td style="max-width:250px;">{{ $report->reason }}</td>
                        <td>
                            @if($report->status === 'open')
                                <span style="color:orange; font-weight:bold;">Ouvert</span>
                            @else
                                <span style="color:green;">Resolu</span>
                            @endif
                        </td>
                        <td>{{ $report->created_at->format('d/m/Y') }}</td>
                        <td>
                            {{-- Resolve report --}}
                            @if($report->status === 'open')
                                <form method="POST"
                                      action="{{ route('admin.reports.resolve', $report) }}"
                                      style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit">Marquer resolu</button>
                                </form>
                            @endif

                            {{-- Suspend / unsuspend reported user --}}
                            @if(! $report->reported->isAdmin())
                                @if(! $report->reported->suspended)
                                    <form method="POST"
                                          action="{{ route('admin.users.suspend', $report->reported) }}"
                                          style="display:inline; margin-left:4px;">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                onclick="return confirm('Suspendre {{ $report->reported->name }} ?')"
                                                style="color:red;">
                                            Suspendre
                                        </button>
                                    </form>
                                @else
                                    <form method="POST"
                                          action="{{ route('admin.users.unsuspend', $report->reported) }}"
                                          style="display:inline; margin-left:4px;">
                                        @csrf @method('PATCH')
                                        <button type="submit">Lever suspension</button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:15px;">{{ $reports->links() }}</div>
    @else
        <p>Aucun signalement pour le moment.</p>
    @endif
</body>
</html>
