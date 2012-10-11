
$(document).ready(function() {
  $(".tabbed-profile-edit").click(function(event){
    event.preventDefault();
    var id = $(this).parent().attr("rel");
    $.fancybox({
      "href" : elgg.get_site_url() + "ajax/view/tabbed_profile/edit?profile_guid=" + id,
      "autoDimensions" : true
      });
  });
});