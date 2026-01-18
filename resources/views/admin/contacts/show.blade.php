@extends('admin.layout')

@section('title', 'Contact Message')

@section('content')
    <div class="admin-header">
        <h1>Message from {{ $contact->name }}</h1>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Messages
            </a>
            <a href="{{ route('admin.contacts.archived') }}" class="btn btn-outline-secondary">
                <i class="fas fa-box-archive me-2"></i>Archived
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card p-4">
                <h5 style="margin-bottom: 20px; color: #2c3e50; font-weight: 700;">Message Content</h5>
                <p style="line-height: 1.8; white-space: pre-wrap; margin: 0;">{{ $contact->message }}</p>
            </div>

            <div class="admin-card p-4" style="margin-top: 16px;" id="emailCustomer">
                <h5 style="margin-bottom: 20px; color: #2c3e50; font-weight: 700;">
                    <i class="fas fa-paper-plane me-2"></i>Email Customer
                </h5>

                <form action="{{ route('admin.contacts.email', $contact) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="subject" class="form-label" style="font-weight: 600;">Subject</label>
                        <input
                            type="text"
                            class="form-control"
                            id="subject"
                            name="subject"
                            maxlength="150"
                            value="{{ old('subject', 'Re: Your message to CoreFive Gadgets') }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label" style="font-weight: 600;">Message</label>
                        <textarea
                            class="form-control"
                            id="message"
                            name="message"
                            rows="7"
                            maxlength="5000"
                            required
                        >{{ old('message') }}</textarea>
                        <div class="form-text" style="color: #7f8c8d;">Sent from CoreFive Gadgets.</div>
                    </div>

                    <button type="submit" class="btn btn-admin btn-admin-primary">
                        <i class="fas fa-paper-plane me-2"></i>Send Email
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card p-4">
                <h5 style="margin-bottom: 20px; color: #2c3e50; font-weight: 700;">
                    <i class="fas fa-user me-2"></i>Sender Details
                </h5>
                <p style="margin: 0;">
                    <strong>{{ $contact->name }}</strong>
                </p>
                <p style="margin: 8px 0 0 0; color: #7f8c8d;">
                    <i class="fas fa-envelope me-2"></i>{{ $contact->email }}
                </p>
                <p style="margin: 8px 0 0 0; color: #7f8c8d;">
                    <i class="fas fa-phone-alt me-2"></i>{{ $contact->contact ?? 'N/A' }}
                </p>
                <p style="margin: 15px 0 0 0; padding: 12px; background: #f8f9fa; border-radius: 8px; color: #7f8c8d; margin-bottom: 0;">
                    <strong>Received:</strong><br>
                    {{ $contact->created_at->format('M d, Y h:i A') }}
                </p>

                <button
                    type="button"
                    class="btn btn-admin btn-admin-danger w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#archiveContactModal"
                    data-action="{{ route('admin.contacts.destroy', $contact) }}"
                    data-name="{{ $contact->name }}"
                    style="margin-top: 15px;"
                >
                    <i class="fas fa-box-archive me-2"></i>Archive Message
                </button>

                <a href="#emailCustomer" class="btn btn-admin btn-admin-primary w-100" style="margin-top: 10px;">
                    <i class="fas fa-envelope me-2"></i>Email Customer
                </a>
            </div>
        </div>
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
