@extends('admin.layout')

@section('title', 'Archived Messages')

@section('content')
    <div class="admin-header">
        <div>
            <h1>Archived Messages</h1>
            <div class="mt-2" style="display:flex; gap:8px; flex-wrap: wrap;">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-admin" style="background: #f2f4f7; color: #2c3e50; border-radius: 10px; font-weight: 700;">Active</a>
                <a href="{{ route('admin.contacts.archived') }}" class="btn btn-sm btn-admin" style="background: #1565c0; color: #fff; border-radius: 10px; font-weight: 700;">Archived</a>
            </div>
            <div class="mt-2" style="color:#7f8c8d; font-weight:600;">
                Messages here are not visible in Active. You can restore them or delete forever.
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
                        <th>Archived</th>
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
                            <td>{{ optional($contact->deleted_at)->format('M d, Y') }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-admin btn-admin-success js-restore-contact"
                                    data-bs-toggle="modal"
                                    data-bs-target="#restoreContactModal"
                                    data-action="{{ route('admin.contacts.restore', $contact->id) }}"
                                    data-name="{{ $contact->name }}"
                                >
                                    <i class="fas fa-rotate-left"></i> Restore
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-admin btn-admin-danger js-delete-forever-contact"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteForeverContactModal"
                                    data-action="{{ route('admin.contacts.force-destroy', $contact->id) }}"
                                    data-name="{{ $contact->name }}"
                                >
                                    <i class="fas fa-trash"></i> Delete Forever
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #7f8c8d; padding: 40px;">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px;"></i>
                                <p>No archived messages</p>
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

    <!-- Restore Modal -->
    <div class="modal fade" id="restoreContactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 14px; overflow:hidden;">
                <div class="modal-header" style="background: #e7f1ff;">
                    <h5 class="modal-title" style="font-weight: 800; color:#0d47a1;">Restore message?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color:#2c3e50;">
                    <div style="font-weight:700;">This message will be restored to Active.</div>
                    <div class="mt-2" style="color:#7f8c8d;">From: <span id="restoreContactName" style="font-weight:700;"></span></div>
                </div>
                <div class="modal-footer" style="border-top: 0;">
                    <button type="button" class="btn btn-admin" style="background:#f2f4f7;" data-bs-dismiss="modal">Cancel</button>
                    <form id="restoreContactForm" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-admin btn-admin-success">Restore</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Forever Modal -->
    <div class="modal fade" id="deleteForeverContactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 14px; overflow:hidden;">
                <div class="modal-header" style="background: #ffecec;">
                    <h5 class="modal-title" style="font-weight: 800; color:#b91c1c;">Delete forever?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color:#2c3e50;">
                    <div style="font-weight:700;">This will permanently remove the message from the database.</div>
                    <div class="mt-2" style="color:#7f8c8d;">From: <span id="deleteForeverContactName" style="font-weight:700;"></span></div>
                </div>
                <div class="modal-footer" style="border-top: 0;">
                    <button type="button" class="btn btn-admin" style="background:#f2f4f7;" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForeverContactForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-admin btn-admin-danger">Delete Forever</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const restoreModal = document.getElementById('restoreContactModal');
    const restoreName = document.getElementById('restoreContactName');
    const restoreForm = document.getElementById('restoreContactForm');

    if (restoreModal) {
        restoreModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;
            restoreName.textContent = button.getAttribute('data-name') || '';
            restoreForm.setAttribute('action', button.getAttribute('data-action') || '#');
        });
    }

    const delModal = document.getElementById('deleteForeverContactModal');
    const delName = document.getElementById('deleteForeverContactName');
    const delForm = document.getElementById('deleteForeverContactForm');

    if (delModal) {
        delModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;
            delName.textContent = button.getAttribute('data-name') || '';
            delForm.setAttribute('action', button.getAttribute('data-action') || '#');
        });
    }
})();
</script>
@endpush
