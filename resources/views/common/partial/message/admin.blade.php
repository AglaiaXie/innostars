<section class="section">
  <div class="columns">
    <div class="column is-12 has-text-centered">
      <a class="button is-info" onclick="openMessageModal({{$admin->id}})">
        <span class="icon is-small"><i class="fa fa-comments"></i> </span> <span>Send Message To Admin</span>
      </a>
    </div>
  </div>
</section>
@include('common.partial.message-new')
<script>
    function openMessageModal(id) {
        var uid = $("#uid");
        if (uid.val() != id) {
            uid.val(id);
            $("#subject").val('');
            $("#content").val('');
        }
        $("#message_modal").addClass('is-active');
    }

    function closeMessageModal() {
        $("#message_modal").removeClass('is-active');
    }
</script>