tabbed_profile
==============

Users/Groups can have multiple profile pages for different contexts/viewers

This plugin creates tabs on the dashboard for contextual representations of the user/group.  Each version of the
dashboard is privacy controlled with Elgg's regular access system.  Dashboards can either utilize the
default widget layout or an iframe for external content.  The number of widget columns can be configurable
and the profile block can be shown or not.  Additionally for groups the sidebar visibility can be set.

Groups already have visibility set during the creation of the group, and can be changed in the edit
form.  Therefore the default profile for the group has no visibility controls.

User profiles are always public in elgg core, there is an admin setting that can allow users to control
the visibility of the default profiles.

The layout and settings of the default profiles cannot be changed in order to maintain compatibility
if the plugin is subsequently disabled.