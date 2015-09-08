<?php

namespace AU\TabbedProfile;

$group = $vars['entity']->getContainerEntity();
$owner = $group->getOwnerEntity();
?>

<div class="groups-stats">
	<p>
		<b><?php echo elgg_echo("groups:owner"); ?>: </b>
		<?php
		echo elgg_view('output/url', array(
			'text' => $owner->name,
			'value' => $owner->getURL(),
			'is_trusted' => true,
		));
		?>
	</p>
	<p>
		<?php
		echo elgg_echo('groups:members') . ": " . $group->getMembers(0, 0, TRUE);
		?>
	</p>

	<?php
	if (elgg_is_active_plugin('group_tools')) {
		if ($group->isPublicMembership()) {
			$status = elgg_echo("groups:open");
			$id = "group_tools_status_open";
		} else {
			$status = elgg_echo("groups:closed");
			$id = "group_tools_status_closed";
		}

		$status = ucfirst($status);

		echo "<p id='$id'>$status</p>";
	}
	?>
</div>
