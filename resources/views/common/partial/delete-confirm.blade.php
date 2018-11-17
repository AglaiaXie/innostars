<div class="modal" id="deleteConfirmModal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <section class="modal-card-body">
      <p class="modal-card-title">Are you sure that you want to delete this?</p>
      <!-- Content ... -->
    </section>
    <footer class="modal-card-foot">
      <button class="button is-danger left-button" data-id onclick="doDelete(this)">Yes</button>
      <button class="button" onclick="$('#deleteConfirmModal').removeClass('is-active')">Cancel</button>
    </footer>
  </div>
</div>
