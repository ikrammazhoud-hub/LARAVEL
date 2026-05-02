@extends($notificationLayout ?? 'layouts.admin')

@section('header', 'Notifications')
@section('subheader', 'Suivez les alertes et les mises à jour importantes de votre espace.')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- En-tête --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Notifications</h1>
            <p class="text-slate-500 text-sm mt-1">
                {{ $nonLues }} notification{{ $nonLues > 1 ? 's' : '' }} non lue{{ $nonLues > 1 ? 's' : '' }}
            </p>
        </div>
        @if($nonLues > 0)
        <form method="POST" action="{{ route($notificationRoutePrefix . '.marquer-toutes-lues') }}">
            @csrf @method('PATCH')
            <button type="submit"
                class="inline-flex items-center gap-2 text-sm text-brand-600 hover:text-brand-700 font-semibold border border-brand-200 hover:bg-brand-50 px-4 py-2 rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tout marquer comme lu
            </button>
        </form>
        @endif
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Liste --}}
    <div class="space-y-3">
        @forelse($notifications as $notif)
        <div class="bg-white rounded-2xl border {{ !$notif->lu ? 'border-brand-200 shadow-brand-100' : 'border-slate-200' }} shadow-sm p-5 flex items-start gap-4 transition-all">

            {{-- Icône --}}
            <div class="shrink-0 mt-0.5">
                @if($notif->type === 'tache_assignee')
                    <div class="w-10 h-10 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                @elseif($notif->type === 'alerte_machine')
                    <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                @else
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                @endif
            </div>

            {{-- Contenu --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <p class="font-semibold text-slate-800 text-sm">
                        {{ $notif->titre }}
                        @if(!$notif->lu)
                            <span class="inline-block w-2 h-2 bg-brand-500 rounded-full ml-1 align-middle"></span>
                        @endif
                    </p>
                    <span class="text-xs text-slate-400 shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-slate-500 text-sm mt-1">{{ $notif->message }}</p>
            </div>

            {{-- Actions --}}
            <div class="shrink-0 flex items-center gap-1">
                @if(!$notif->lu)
                <form method="POST" action="{{ route($notificationRoutePrefix . '.marquer-lue', $notif) }}">
                    @csrf @method('PATCH')
                    <button type="submit" title="Marquer comme lu"
                        class="text-slate-400 hover:text-brand-600 transition-colors p-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route($notificationRoutePrefix . '.destroy', $notif) }}"
                      onsubmit="return confirm('Supprimer cette notification ?')">
                    @csrf @method('DELETE')
                    <button type="submit" title="Supprimer"
                        class="text-slate-400 hover:text-red-500 transition-colors p-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <p class="text-slate-400 font-medium">Aucune notification</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div>{{ $notifications->links() }}</div>
    @endif

</div>
@endsection
