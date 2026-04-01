var Timer = function(count){
	var countElement = document.getElementsByClassName(count);
	function getTimeRemaining(endtime) {
		const total = Date.parse(endtime) - Date.parse(new Date());
		const seconds = Math.floor((total / 1000) % 60);
		const minutes = Math.floor((total / 1000 / 60) % 60);
		const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
		const days = Math.floor(total / (1000 * 60 * 60 * 24));
		
		return {
			total,
			days,
			hours,
			minutes,
			seconds
		}
	}

	$.fn.initializeClock = function(id, endtime) {
		const clock = document.getElementsByClassName(id)[i];

		if (clock !== undefined){
			var daysSpan = clock.querySelector('.days');
			var hoursSpan = clock.querySelector('.hours');
			var minutesSpan = clock.querySelector('.minutes');
			var secondsSpan = clock.querySelector('.seconds');

			function updateClock() {
				const t = getTimeRemaining(endtime);
			
				daysSpan.innerHTML = ('0' + t.days).slice(-2);
				hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
				minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
				secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
			
				if (t.total <= 0) {
					clearInterval(timeinterval);
				}
	
			}
		
			updateClock();
			const timeinterval = setInterval(updateClock, 1000);
		}
	}

	var data = $('.count_down_block').attr('date'), currentDate = new Date();
	const deadline = new Date(data);

	var i = 0;
	for(i; i <= countElement.length; i++) {
		if(deadline > currentDate) {
			$.fn.initializeClock(count, deadline);
		}
	}
}