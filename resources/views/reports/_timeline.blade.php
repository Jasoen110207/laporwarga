<ul class="timeline">
    <li><strong>Laporan diterima</strong><br><span class="muted small">{{ $report->created_at->translatedFormat('d F Y, H:i') }}</span></li>
    @foreach($report->responses as $r)
        <li>
            <span class="badge badge-{{ $r->status_after }}">{{ $r->status_label }}</span>
            <span class="muted small">{{ $r->created_at->translatedFormat('d F Y, H:i') }}</span>
            @if($r->message)<p style="margin:.4rem 0 0">{{ $r->message }}</p>@endif
        </li>
    @endforeach
</ul>
