(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var container = document.querySelector('.wpcity-cf-container');
		if (!container) return;

		var markBtn = container.querySelector('.wpcity-cf-mark-reviewed');
		if (!markBtn) return;

		markBtn.addEventListener('click', function () {
			var postId = markBtn.dataset.postId;
			if (!postId) return;

			markBtn.disabled = true;
			markBtn.textContent = wpcityCF.markingText || 'Saving...';

			var formData = new FormData();
			formData.append('action', 'wpcity_cf_mark_reviewed');
			formData.append('nonce', wpcityCF.nonce);
			formData.append('post_id', postId);

			fetch(wpcityCF.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: formData
			})
			.then(function (res) { return res.json(); })
			.then(function (data) {
				if (data.success) {
					// Update status display.
					var statusEl = document.getElementById('wpcity-cf-status');
					if (statusEl && data.data.status) {
						statusEl.innerHTML =
							'<p class="wpcity-cf-last-reviewed"><strong>' + 'Last Reviewed:' + '</strong> ' +
							escHtml(data.data.last_reviewed) + '</p>' +
							'<p class="wpcity-cf-indicator wpcity-cf-indicator-' + escHtml(data.data.status.color) + '">' +
							'<span class="wpcity-cf-dot">●</span> ' + escHtml(data.data.status.label) + '</p>';
					}

					markBtn.textContent = wpcityCF.reviewedText || 'Reviewed just now';
					markBtn.classList.add('wpcity-cf-saved');

					setTimeout(function () {
						markBtn.disabled = false;
						markBtn.textContent = wpcityCF.markedText || 'Mark as Reviewed';
						markBtn.classList.remove('wpcity-cf-saved');
					}, 2000);
				} else {
					markBtn.disabled = false;
					markBtn.textContent = wpcityCF.markedText || 'Mark as Reviewed';
				}
			})
			.catch(function () {
				markBtn.disabled = false;
				markBtn.textContent = wpcityCF.markedText || 'Mark as Reviewed';
			});
		});

		function escHtml(str) {
			var div = document.createElement('div');
			div.textContent = str;
			return div.innerHTML;
		}
	});
})();
