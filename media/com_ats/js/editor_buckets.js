/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

objSeparator = {
	separator:'---------------'
};
objBucketsButton = {
	name		: 'Buckets',
	className	: 'bucket',
	replaceWith:function(markitup) {
		SqueezeBox.fromElement(document.getElementById('atsBucketsDialog'), {
			parse: 'rel'
		});
		return false;
	}
}
myBBCodeSettings.markupSet.push(objSeparator);
myBBCodeSettings.markupSet.push(objBucketsButton);