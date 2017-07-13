/*!
 * Manage our groups
 * 
 * Based off the Craft fields.js file
 */

(function($) {

var GroupsAdmin = Garnish.Base.extend({

	$groups: null,
	$selectedGroup: null,

	init: function(settings)
	{
		// make settings globally available
		window.settings = settings;

		this.$groups = $(settings.groupsSelector);
		this.$selectedGroup = this.$groups.find('a.sel:first');
		this.addListener($(settings.newGroupButtonSelector), 'activate', 'addNewGroup');

		var $groupSettingsBtn = $(settings.groupSettingsSelector);
	
		if ($groupSettingsBtn.length)
		{

			var menuBtn = $groupSettingsBtn.data('menubtn');
			
			menuBtn.settings.onOptionSelect = $.proxy(function(elem)
			{
				var action = $(elem).data('action');

				switch (action)
				{
					case 'rename':
					{
						this.renameSelectedGroup();
						break;
					}
					case 'delete':
					{
						this.deleteSelectedGroup();
						break;
					}
				}
			}, this);
		}
	},

	addNewGroup: function()
	{
		var name = this.promptForGroupName();

		if (name)
		{
			var data = {
				name: name
			};

			Craft.postActionRequest(settings.newGroupAction, data, $.proxy(function(response)
			{
				if (response.success)
				{
					location.href = Craft.getUrl(settings.newGroupOnSuccessUrlBase+response.group.id);
				}
				else
				{
					var errors = this.flattenErrors(response.errors);
					alert(Craft.t('sproutReports', settings.newGroupOnErrorMessage)+"\n\n"+errors.join("\n"));
				}

			}, this));
		}
	},

	renameSelectedGroup: function()
	{
		var oldName = this.$selectedGroup.text(),
			newName = this.promptForGroupName(oldName);

		if (newName && newName != oldName)
		{
			var data = {
				id:   this.$selectedGroup.data('id'),
				name: newName
			};

			Craft.postActionRequest(settings.renameGroupAction, data, $.proxy(function(response)
			{
				if (response.success)
				{
					this.$selectedGroup.text(response.group.name);
					Craft.cp.displayNotice(Craft.t('sproutReports', (settings.renameGroupOnSuccessMessage)));
				}
				else
				{
					var errors = this.flattenErrors(response.errors);
					alert(Craft.t('sproutReports', settings.renameGroupOnErrorMessage)+"\n\n"+errors.join("\n"));
				}

			}, this));
		}
	},

	promptForGroupName: function(oldName)
	{
		return prompt(Craft.t('sproutReports', settings.promptForGroupNameMessage), oldName);
	},

	deleteSelectedGroup: function()
	{
		if (confirm(Craft.t('sprout-reports', settings.deleteGroupConfirmMessage)))
		{
			var data = {
				id: this.$selectedGroup.data('id')
			};

			Craft.postActionRequest(settings.deleteGroupAction, data, $.proxy(function(response)
			{
				if (response.success)
				{
					location.href = Craft.getUrl(settings.deleteGroupOnSuccessUrl);
				}
				else
				{
					alert(Craft.t('sprout-reports', settings.deleteGroupOnErrorMessage));
				}
			}, this));
		}
	},

	flattenErrors: function(responseErrors)
	{
		var errors = [];

		for (var attribute in responseErrors)
		{
			errors = errors.concat(response.errors[attribute]);
		}

		return errors;
	}
});

// @TODO - How can we move this to the page?
Garnish.$doc.ready(function()
{
	Craft.GroupsAdmin = new GroupsAdmin({
		groupsSelector: '#groups',
		newGroupButtonSelector: '#newgroupbtn',
		groupSettingsSelector: '#groupsettingsbtn',

		newGroupAction: 'sprout-reports/report-group/save-group',
		newGroupOnSuccessUrlBase: 'sprout-reports/reports/',
		newGroupOnErrorMessage: 'Could not create the group:',

		renameGroupAction: 'sprout-reports/report-group/save-group',
		renameGroupOnSuccessMessage: 'Group renamed.',
		renameGroupOnErrorMessage: 'Could not rename the group:',

		promptForGroupNameMessage: 'What do you want to name your group?',

		deleteGroupConfirmMessage: 'Are you sure you want to delete this group and all its reports?',
		deleteGroupAction: 'sprout-reports/report-group/delete-group',
		deleteGroupOnSuccessUrl: 'sprout-reports/reports',
		deleteGroupOnErrorMessage: 'Could not delete the group.',
	});
});

})(jQuery);
