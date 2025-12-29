@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4>Panel de Notificaciones OTP <span class="badge bg-secondary" id="pendingCount">{{ $pendingCount }}</span></h4>
        <button class="btn btn-sm btn-outline-secondary" id="copyPending">Copiar pendientes</button>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Email</th>
                    <th>Código</th>
                    <th>WhatsApp</th>
                    <th>Solicitado</th>
                    <th>Notas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="otpTableBody">
                @foreach($notifications as $n)
                    <tr class="{{ $n->status === 'pending' ? 'table-warning' : '' }}" data-id="{{ $n->id }}">
                        <td><span class="badge bg-{{ $n->status === 'pending' ? 'warning' : ($n->status === 'verified' ? 'success' : 'secondary') }}">{{ $n->status }}</span></td>
                        <td>{{ $n->email }}</td>
                        <td><code>{{ $n->code }}</code></td>
                        <td>{{ $n->whatsapp ?? '-' }}</td>
                        <td>{{ optional($n->requested_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $n->notes }}</td>
                        <td class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary copy-btn" data-code="{{ $n->code }}">Copiar</button>
                            @if($n->whatsapp)
                                <a class="btn btn-sm btn-success" href="{{ route('admin.otp.sendWhatsApp', $n) }}" target="_blank">WhatsApp</a>
                            @endif
                            @if($n->status === 'pending')
                                <form action="{{ route('admin.otp.markShared', $n) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary">Marcar compartido</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $notifications->links() }}
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Copiar cA3digo individual
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', () => navigator.clipboard.writeText(btn.dataset.code));
    });

    // Copiar todos los pendientes
    document.getElementById('copyPending')?.addEventListener('click', () => {
        const codes = Array.from(document.querySelectorAll('tr.table-warning code')).map(c => c.textContent).join(', ');
        if (codes) navigator.clipboard.writeText(codes);
    });

    // UI para activar sonido (por polA-tica del navegador)
    const header = document.querySelector('.container .d-flex');
    const soundBtn = document.createElement('button');
    soundBtn.className = 'btn btn-sm btn-outline-primary ms-2';
    soundBtn.textContent = 'Activar sonido';
    header?.appendChild(soundBtn);

    let soundEnabled = false;
    const beepAudio = new Audio('data:audio/wav;base64,UklGRiQAAABXQVZFZm10IBAAAAABAAEAIlYAAESsAAACABAAZGF0YQgAAAAA');

    soundBtn.addEventListener('click', () => {
        beepAudio.play().catch(() => {});
        soundEnabled = true;
        soundBtn.textContent = 'Sonido activado';
        soundBtn.classList.remove('btn-outline-primary');
        soundBtn.classList.add('btn-success');
    });

    function playBeep() {
        if (!soundEnabled) return;
        beepAudio.currentTime = 0;
        beepAudio.play().catch(() => {});
    }

    // Toast liviano
    function showToast(message) {
        const toast = document.createElement('div');
        toast.textContent = message;
        toast.style.position = 'fixed';
        toast.style.right = '16px';
        toast.style.bottom = '16px';
        toast.style.padding = '12px 16px';
        toast.style.background = '#0d6efd';
        toast.style.color = '#fff';
        toast.style.borderRadius = '6px';
        toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
        toast.style.zIndex = '1080';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    // Polling de pendientes cada 3s
    const pendingBadge = document.getElementById('pendingCount');
    let lastCount = parseInt(pendingBadge?.textContent || '0');
    const baseTitle = 'Panel de Notificaciones OTP';
    const tbody = document.getElementById('otpTableBody');
    let lastSeenId = Math.max(
        0,
        ...Array.from(tbody?.querySelectorAll('tr[data-id]') || []).map(tr => parseInt(tr.getAttribute('data-id') || '0') || 0)
    );

    async function pollPending() {
        try {
            const res = await fetch('{{ route('admin.otp.pendingCount') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            const newCount = parseInt(data.count ?? 0);
            if (Number.isFinite(newCount)) {
                if (newCount > lastCount) {
                    showToast(`Nuevos OTP pendientes: +${newCount - lastCount}`);
                    playBeep();
                    setTimeout(() => window.location.reload(), 600);
                }
                lastCount = newCount;
                if (pendingBadge) pendingBadge.textContent = newCount;
                document.title = newCount > 0
                    ? `🔵 (${newCount}) ${baseTitle}`
                    : baseTitle;
            }
        } catch (e) {
            // silencioso
        }

        try {
            const resLatest = await fetch('{{ route('admin.otp.latest') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const dataLatest = await resLatest.json();
            const latestId = parseInt(dataLatest.latest_id ?? 0);
            if (Number.isFinite(latestId) && latestId > lastSeenId) {
                showToast('Nuevo OTP detectado...');
                playBeep();
                lastSeenId = latestId;
                setTimeout(() => window.location.reload(), 600);
            }
        } catch (e) {
            // silencioso
        }
    }

    setInterval(pollPending, 3000);
});
</script>
@endpush
@endsection
