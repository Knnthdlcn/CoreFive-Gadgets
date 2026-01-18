@extends('admin.layout')

@section('title', 'Contact Messages')

@section('content')
    <div class="admin-header">
        <div>
            <h1>Contact Messages</h1>
            @php($unreadOnly = request()->boolean('unread'))
            <div class="mt-2" style="display:flex; gap:8px; flex-wrap: wrap;">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-admin" style="background: {{ $unreadOnly ? '#f2f4f7' : '#1565c0' }}; color: {{ $unreadOnly ? '#2c3e50' : '#fff' }}; border-radius: 10px; font-weight: 700;">All</a>
                <a href="{{ route('admin.contacts.index', ['unread' => 1]) }}" class="btn btn-sm btn-admin" style="background: {{ $unreadOnly ? '#b91c1c' : '#f2f4f7' }}; color: {{ $unreadOnly ? '#fff' : '#2c3e50' }}; border-radius: 10px; font-weight: 700;">Unread</a>
                <a href="{{ route('admin.contacts.archived') }}" class="btn btn-sm btn-admin" style="background: #f2f4f7; color: #2c3e50; border-radius: 10px; font-weight: 700;">Archived</a>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Message Preview</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr>
                            <td><strong>{{ $contact->name }}</strong></td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->contact ?? 'N/A' }}</td>
                            <td>{{ Str::limit($contact->message, 50) }}</td>
                            <td>{{ $contact->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-admin btn-admin-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.contacts.show', $contact) }}#emailCustomer" class="btn btn-sm btn-admin btn-admin-success">
                                    <i class="fas fa-envelope"></i> Email
                                </a>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-admin btn-admin-danger js-archive-contact"
                                    data-bs-toggle="modal"
                                    data-bs-target="#archiveContactModal"
                                    data-action="{{ route('admin.contacts.destroy', $contact) }}"
                                    data-name="{{ $contact->name }}"
                                >
                                    <i class="fas fa-box-archive"></i> Archive
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #7f8c8d; padding: 40px;">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px;"></i>
                                <p>No messages found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contacts->hasPages())
            <div class="p-4">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>

    <!-- Archive Modal -->
    <div class="modal fade" id="archiveContactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 14px; overflow:hidden;">
                <div class="modal-header" style="background: #fff7e6;">
                    <h5 class="modal-title" style="font-weight: 800; color:#a16207;">Archive this message?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color:#2c3e50;">
                    <div style="font-weight:700;">This will move the message to Archived (Recently Deleted).</div>
                    <div class="mt-2" style="color:#7f8c8d;">From: <span id="archiveContactName" style="font-weight:700;"></span></div>
                </div>
                <div class="modal-footer" style="border-top: 0;">
                    <button type="button" class="btn btn-admin" style="background:#f2f4f7;" data-bs-dismiss="modal">Cancel</button>
                    <form id="archiveContactForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-admin btn-admin-danger">
                            <i class="fas fa-box-archive me-2"></i>Archive
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const modal = document.getElementById('archiveContactModal');
    const nameEl = document.getElementById('archiveContactName');
    const form = document.getElementById('archiveContactForm');

    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;
        nameEl.textContent = button.getAttribute('data-name') || '';
        form.setAttribute('action', button.getAttribute('data-action') || '#');
    });
})();
</script>
@endpush
