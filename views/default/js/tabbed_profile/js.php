elgg.provide('elgg.tabbed_profile');

elgg.tabbed_profile.init = function() {
    // Initialize tab sorting
    elgg.tabbed_profile.sortable();
};


/**
 * Handle section and game sorting via jQuery UI sortable
 */
elgg.tabbed_profile.sortable = function() {

    $('#profile-tabs-container').sortable({
        items: '.tabbed-profile-sortable',
        update: function(event, ui) {
          var item = $(ui.item);
          var id = item.children('a:first-child').attr('rel');
          var order = 1;
          
          if (item.hasClass('tabbed-profile-sortable')) {
            // set the order
            item.prevAll().each(function() {
              order++;
            });
            
            elgg.tabbed_profile.update_order(id, order);
          }
        }
    });
};


elgg.tabbed_profile.update_order = function(id, order) {
  elgg.action('tabbed_profile/order', {
    data: {
      guid: id,
      order: order
    }
  });
}

elgg.register_hook_handler('init', 'system', elgg.tabbed_profile.init);


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