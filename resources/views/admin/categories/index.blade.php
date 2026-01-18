@extends('admin.layout')

@section('title', 'Categories')

@section('content')
    <div class="admin-header">
        <h1>Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-admin-primary">
            <i class="fas fa-plus me-2"></i>Add New Category
        </a>
    </div>

    <div class="admin-card p-4">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td><strong>#{{ $category->id }}</strong></td>
                            <td>{{ $category->name }}</td>
                            <td><span class="badge" style="background: #1565c0; color: white;">{{ $category->products_count ?? 0 }}</span></td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-admin-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-admin-danger delete-category-btn" data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No categories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #1f1f1f; border: none; border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 1px solid #333; background: #1f1f1f;">
                    <h5 class="modal-title" id="deleteCategoryLabel" style="color: #fff; font-weight: 700;">
                        <i class="fas fa-exclamation-triangle me-2" style="color: #ff6b6b;"></i>Delete Category
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(1.2);"></button>
                </div>
                <div class="modal-body" style="background: #1f1f1f; color: #fff; padding: 24px;">
                    <p style="margin-bottom: 0; font-size: 1rem;">
                        Are you sure you want to delete the category <strong id="categoryNameDisplay"></strong>?
                    </p>
                    <p style="color: #999; font-size: 0.9rem; margin-top: 12px;">This action cannot be undone.</p>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #333; background: #1f1f1f; padding: 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: #444; border: none; color: #fff;">
                        Cancel
                    </button>
                    <form id="deleteCategoryForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="background: #ff6b6b; border: none; color: #fff;">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal {
            background: rgba(0, 0, 0, 0.7) !important;
        }
        
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.7) !important;
        }
    </style>

    <script>
        const deleteCategoryModal = document.getElementById('deleteCategoryModal');
        const deleteCategoryForm = document.getElementById('deleteCategoryForm');
        const categoryNameDisplay = document.getElementById('categoryNameDisplay');
        let currentCategoryId = null;
        
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-category-btn')) {
                e.preventDefault();
                e.stopPropagation();
                
                const btn = e.target.closest('.delete-category-btn');
                currentCategoryId = btn.getAttribute('data-category-id');
                const categoryName = btn.getAttribute('data-category-name');
                
                categoryNameDisplay.textContent = categoryName;
                deleteCategoryForm.action = '/admin/categories/' + currentCategoryId;
                
                const modal = new bootstrap.Modal(deleteCategoryModal);
                modal.show();
            }
        }, true);
        
        deleteCategoryForm.addEventListener('submit', function(e) {
            // Let the form submit normally
            // The page will reload with the updated list
        });
    </script>

@endsection
