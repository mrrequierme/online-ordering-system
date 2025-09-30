    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="deleteConfirmLabel">Confirm Delete</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to remove <strong id="deleteItemName"></strong> from your cart?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Quantity Modal -->
    <div class="modal fade" id="editQtyModal" tabindex="-1" aria-labelledby="editQtyLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="editQtyLabel">Edit Quantity</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Edit quantity for <strong id="editItemName"></strong></p>
            <input type="number" id="editQtyInput" class="form-control" min="1">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" id="confirmEditBtn" class="btn btn-warning">Save</button>
          </div>
        </div>
      </div>
    </div>