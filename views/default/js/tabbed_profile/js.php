$(document).ready(function() {

  // load the add/edit form in a lightbox
  $(".tabbed-profile-edit").click(function(event){
    event.preventDefault();
    var id = $(this).parent().attr("rel");
    $.fancybox({
      "href" : elgg.get_site_url() + "ajax/view/tabbed_profile/edit?profile_guid=" + id,
      "autoDimensions" : true
      });
  });
  
  
  // switch between conditional parts of the form
  $("#tabbed-profile-profile-type").live('change', function(event) {
    var type = $(this).val();
    
    if (type == 'widgets') {
      $(".tabbed-profile-widgets-form").show();
      $(".tabbed-profile-iframe-form").hide();
    }
    else {
      $(".tabbed-profile-iframe-form").show();
      $(".tabbed-profile-widgets-form").hide();
    }
  });
  
  
  // deletion confirmation
  $("#tabbed-profile-delete-profile").live('click', function(event) {
    var isChecked = $(this).is(':checked');
    
    if (isChecked) {
      if (confirm(elgg.echo('tabbed_profile:delete:confirm'))) {
        $("#tabbed-profile-delete-profile").attr('checked', 'checked');
      }
      else {
        $("#tabbed-profile-delete-profile").removeAttr('checked');
      }
    }
  });
});