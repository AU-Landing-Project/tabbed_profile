<?php
  $group = $vars['entity']->getContainerEntity();
?>

<div class="groups-profile-fields elgg-body">
		<?php
			echo elgg_view('groups/profile/fields', array('entity' => $group));
		?>
</div>