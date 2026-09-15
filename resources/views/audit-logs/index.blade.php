<x-app-layout>
    <x-slot name="header">
        <div class="audit-heading">
            <div>
                <p class="eyebrow">Administration / Security</p>
                <h1>Audit Log</h1>
            </div>
            <div class="audit-lock" aria-label="Administrators only">
                <span aria-hidden="true">⌘</span>
                <div><strong>Admin only</strong><small>Restricted access</small></div>
            </div>
        </div>
    </x-slot>

    <div class="audit-page">
        <section class="audit-summary">
            <div>
                <span class="audit-kicker">System activity</span>
                <strong>{{ number_format($logs->total()) }}</strong>
                <small>logged actions</small>
            </div>
            <div>
                <span class="audit-kicker">Current view</span>
                <strong>{{ $logs->count() }}</strong>
                <small>entries on this page</small>
            </div>
            <div>
                <span class="audit-kicker">Access</span>
                <strong>Admin</strong>
                <small>only</small>
            </div>
        </section>

        <section class="audit-panel">
            <form method="GET" action="{{ route('audit-logs.index') }}" class="audit-filters">
                <div class="audit-field">
                    <label for="action">Action</label>
                    <select id="action" name="action">
                        <option value="">All actions</option>
                        @foreach ($actions as $action)
                            <option value="{{ $action }}" @selected(request('action') === $action)>{{ ucwords(str_replace(['_', '-'], ' ', $action)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="audit-field">
                    <label for="entity_type">Record type</label>
                    <select id="entity_type" name="entity_type">
                        <option value="">All records</option>
                        @foreach ($entityTypes as $entityType)
                            <option value="{{ $entityType }}" @selected(request('entity_type') === $entityType)>{{ class_basename($entityType) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="audit-field">
                    <label for="user_id">User</label>
                    <select id="user_id" name="user_id">
                        <option value="">All users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="audit-actions">
                    <button type="submit" class="button button-red">Filter</button>
                    @if (request()->hasAny(['action', 'entity_type', 'user_id']))
                        <a href="{{ route('audit-logs.index') }}" class="button button-light">Clear</a>
                    @endif
                </div>
            </form>
        </section>

        <section class="audit-panel audit-table-panel">
            <div class="audit-table-head">
                <div>
                    <span class="audit-kicker">Activity history</span>
                    <h2>Recent actions</h2>
                </div>
                <span class="audit-readonly">Read-only record</span>
            </div>

            <div class="audit-table-wrap">
                <table class="audit-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>User</th>
                            <th>Record</th>
                            <th>IP address</th>
                            <th>Date &amp; time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>
                                    <span class="audit-action">{{ ucwords(str_replace(['_', '-'], ' ', $log->action)) }}</span>
                                </td>
                                <td>
                                    <div class="audit-user">
                                        <span>{{ strtoupper(substr($log->user?->name ?? 'System', 0, 1)) }}</span>
                                        <div><strong>{{ $log->user?->name ?? 'System' }}</strong><small>{{ $log->user?->email ?? 'Automated action' }}</small></div>
                                    </div>
                                </td>
                                <td>
                                    @if ($log->entity_type)
                                        <strong class="audit-record-type">{{ class_basename($log->entity_type) }}</strong>
                                        @if ($log->entity_id)<small class="audit-record-id">#{{ $log->entity_id }}</small>@endif
                                    @else
                                        <span class="audit-muted">System</span>
                                    @endif
                                </td>
                                <td><code>{{ $log->ip_address ?: '—' }}</code></td>
                                <td><strong>{{ $log->created_at?->format('M d, Y') }}</strong><small class="audit-date">{{ $log->created_at?->format('H:i') }}</small></td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="audit-empty"><strong>No audit entries found</strong><span>Try clearing the filters or wait for new activity to be recorded.</span></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="audit-pagination">{{ $logs->links() }}</div>
            @endif
        </section>
    </div>

    <style>
        .audit-heading{display:flex;align-items:center;justify-content:space-between;gap:24px}.audit-heading h1{margin:4px 0 8px;color:#002a5c;font:700 clamp(25px,3vw,34px)/1.15 Merriweather,serif}.audit-heading p:not(.eyebrow){margin:0;color:#64748b;font-size:13px}.audit-lock{display:flex;align-items:center;gap:11px;padding:10px 14px;border:1px solid #d8e3ef;border-radius:14px;background:#fff}.audit-lock>span{display:grid;place-items:center;width:34px;height:34px;border-radius:10px;background:#eef5fb;color:#002a5c;font-weight:800}.audit-lock strong,.audit-lock small{display:block}.audit-lock strong{color:#002a5c;font-size:12px}.audit-lock small{margin-top:2px;color:#94a3b8;font-size:10px}.audit-page{padding:0 0 34px}.audit-summary{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px}.audit-summary>div{padding:20px 22px;background:#fff;border:1px solid #d8e3ef;border-top:3px solid #002a5c}.audit-kicker{display:block;color:#94a3b8;font-size:9px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase}.audit-summary strong{display:block;margin-top:8px;color:#002a5c;font:700 25px/1 Merriweather,serif}.audit-summary small{display:block;margin-top:5px;color:#64748b;font-size:10px}.audit-panel{background:#fff;border:1px solid #d8e3ef;margin-bottom:16px}.audit-filters{display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:12px;align-items:end;padding:18px}.audit-field label{display:block;margin-bottom:6px;color:#64748b;font-size:10px;font-weight:800;letter-spacing:.5px}.audit-field select{width:100%;min-height:40px;padding:0 11px;border:1px solid #d8e3ef;border-radius:9px;background:#fff;color:#334155;font-size:12px}.audit-actions{display:flex;gap:7px}.audit-actions .button{min-height:40px}.button-light{display:inline-flex;align-items:center;justify-content:center;padding:0 15px;border:1px solid #d8e3ef;border-radius:9px;background:#f7fbff;color:#002a5c;font-size:12px;font-weight:700;text-decoration:none}.audit-table-head{display:flex;align-items:center;justify-content:space-between;padding:22px;border-bottom:1px solid #e8eef4}.audit-table-head h2{margin:5px 0 0;color:#002a5c;font:700 20px/1.2 Merriweather,serif}.audit-readonly{padding:6px 9px;border-radius:999px;background:#edf7f1;color:#287453;font-size:9px;font-weight:800;letter-spacing:.8px;text-transform:uppercase}.audit-table-wrap{overflow-x:auto}.audit-table{width:100%;border-collapse:collapse}.audit-table th{padding:11px 18px;background:#f8fbfe;color:#94a3b8;border-bottom:1px solid #dfe8f0;font-size:9px;font-weight:800;letter-spacing:1px;text-align:left;text-transform:uppercase}.audit-table td{padding:15px 18px;border-bottom:1px solid #eef2f6;color:#334155;font-size:12px;vertical-align:middle}.audit-table tbody tr:hover{background:#fbfdff}.audit-action{display:inline-flex;padding:6px 9px;border-radius:8px;background:#eef5fb;color:#075487;font-size:10px;font-weight:800}.audit-user{display:flex;align-items:center;gap:9px}.audit-user>span{display:grid;place-items:center;width:31px;height:31px;border-radius:50%;background:#002a5c;color:#fff;font-size:10px;font-weight:800}.audit-user strong,.audit-user small,.audit-table td small{display:block}.audit-user small,.audit-record-id,.audit-date{margin-top:3px;color:#94a3b8;font-size:9px}.audit-record-type{color:#334155;font-size:11px}.audit-table code{padding:4px 6px;border-radius:6px;background:#f5f7fa;color:#64748b;font-size:10px}.audit-muted{color:#94a3b8}.audit-empty{padding:45px 20px;text-align:center}.audit-empty strong{display:block;color:#334155;font-size:13px}.audit-empty span{display:block;margin-top:5px;color:#94a3b8;font-size:11px}.audit-pagination{padding:16px 18px;border-top:1px solid #eef2f6}@media(max-width:850px){.audit-filters{grid-template-columns:1fr 1fr}.audit-actions{grid-column:1/-1}.audit-summary{grid-template-columns:1fr}.audit-heading{align-items:flex-start;flex-direction:column}}@media(max-width:560px){.audit-filters{grid-template-columns:1fr}.audit-actions{grid-column:auto}.audit-lock{width:100%}}
    </style>
</x-app-layout>
