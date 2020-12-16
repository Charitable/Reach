/*--------------------------------------------------------
 * Campaign Countdown
---------------------------------------------------------*/
REACH.Countdown = ( function( $ ) {

	// Start the countdown script
	var startCountdown = function() {
		var $countdown = $('.countdown');

		if ($countdown.length) {

			$countdown.countdown({
				until: $.countdown.UTCDate( REACH_CROWDFUNDING.timezone_offset, new Date( $countdown.data().enddate ) ),
				format: 'dHMS',
				labels : [REACH_CROWDFUNDING.years, REACH_CROWDFUNDING.months, REACH_CROWDFUNDING.weeks, REACH_CROWDFUNDING.days, REACH_CROWDFUNDING.hours, REACH_CROWDFUNDING.minutes, REACH_CROWDFUNDING.seconds],
				labels1 : [REACH_CROWDFUNDING.year, REACH_CROWDFUNDING.month, REACH_CROWDFUNDING.week, REACH_CROWDFUNDING.day, REACH_CROWDFUNDING.hour, REACH_CROWDFUNDING.minute, REACH_CROWDFUNDING.second]
			});
		}

		return $countdown;
	}

	return {
		init : function() {
			startCountdown();
		}
	};
})( jQuery );