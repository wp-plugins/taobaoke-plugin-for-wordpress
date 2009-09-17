function gotall_tracking_ads(params) {
    var gotall_url = 'http://dafang-blog.appspot.com/save?' + params;

	var imgobj = jQuery("#gotall-img-dispatch-www-gotall-net");

	if (undefined == imgobj || undefined == imgobj.attr('src')) {
		jQuery(document.body).append('<img id="gotall-img-dispatch-www-gotall-net" src="' + gotall_url + '" ref="gotall" width=0 height=0 style="display:none;" />');
	}
	else {
		imgobj.attr('src', gotall_url);
	}
}

jQuery(document).ready(function() {
    var item_id_list = '';

	jQuery('.taobaoke-status-tracking-by-gotall-net').each(function() {
		var a = jQuery(this);
		var href = a.attr('href');

		// Check if the a tag has a href, if not, stop for the current link
		if ( href == undefined )
			return;

        var item_id = a.attr('class');

        // Add the tracking code
        a.click(function() {
            //tracking the TaoBaoKe AD click status
            var ad_item_id = a.attr('class');

            gotall_tracking_ads('type=ad_clicks&' + 'item_id=' + encodeURI(ad_item_id));

            return true;
        });

        item_id_list = item_id_list + ',' + item_id;
	});
});
