<div class="modal" id="saveNotifyModal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <section class="modal-card-body">
      <p class="modal-card-title">You have unsaved information.</p>
      <!-- Content ... -->
    </section>
    <footer class="modal-card-foot">
      <a class="button is-danger" id="leftButton">Leave without saving</a>
      <button class="button" onclick="$('#saveNotifyModal').removeClass('is-active')">Cancel</button>
    </footer>
  </div>
</div>
