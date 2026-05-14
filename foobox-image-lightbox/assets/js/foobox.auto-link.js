(function (window, document) {
	'use strict';

	window.FOOBOX_AUTO_LINK_IMAGES = function ($) {
		if (!$ && window.jQuery) {
			$ = window.jQuery;
		}
		if (!$) {
			return;
		}

		var imageSelector = 'img[class*=wp-image-]';
		var excludedSelector = '.nofoobox, .nolightbox';
		var imageExtensionRegex = /\.(?:jpe?g|png|gif|webp|bmp|svg)$/i;
		var thumbnailSizeRegex = /-\d+x\d+(?=\.(?:jpe?g|png|gif|webp|bmp|svg)$)/i;

		function splitUrl(url) {
			var parts = { base: url, query: '', hash: '' };
			var hashIndex = parts.base.indexOf('#');
			if (hashIndex !== -1) {
				parts.hash = parts.base.substring(hashIndex);
				parts.base = parts.base.substring(0, hashIndex);
			}
			var queryIndex = parts.base.indexOf('?');
			if (queryIndex !== -1) {
				parts.query = parts.base.substring(queryIndex);
				parts.base = parts.base.substring(0, queryIndex);
			}
			return parts;
		}

		function isUsableImageUrl(url) {
			if (typeof url !== 'string' || url.length === 0) {
				return false;
			}
			url = url.replace(/^\s+|\s+$/g, '');
			if (url.indexOf('data:') === 0 || url.indexOf('blob:') === 0) {
				return false;
			}
			return imageExtensionRegex.test(splitUrl(url).base);
		}

		function absoluteUrl(url) {
			var anchor = document.createElement('a');
			anchor.href = url;
			return anchor.href;
		}

		function comparableUrl(url) {
			var anchor = document.createElement('a');
			anchor.href = url;
			return anchor.protocol + '//' + anchor.host + anchor.pathname;
		}

		function sameUrl(urlA, urlB) {
			return comparableUrl(urlA) === comparableUrl(urlB);
		}

		function getFirstUsableAttribute($img, attributes) {
			var value, i;
			for (i = 0; i < attributes.length; i++) {
				value = $img.attr(attributes[i]);
				if (isUsableImageUrl(value)) {
					return absoluteUrl(value);
				}
			}
			return '';
		}

		function getSourceUrl($img) {
			var img = $img.get(0);
			var candidates = [
				$img.attr('src'),
				$img.attr('data-src'),
				$img.attr('data-lazy-src'),
				$img.attr('data-original'),
				img ? img.currentSrc : ''
			];
			var i;
			for (i = 0; i < candidates.length; i++) {
				if (isUsableImageUrl(candidates[i])) {
					return absoluteUrl(candidates[i]);
				}
			}
			return '';
		}

		function getLargestSrcsetUrl(srcset) {
			if (typeof srcset !== 'string' || srcset.length === 0) {
				return '';
			}
			var candidates = srcset.split(',');
			var bestUrl = '';
			var bestWidth = 0;
			var i, candidate, parts, url, descriptor, width;
			for (i = 0; i < candidates.length; i++) {
				candidate = candidates[i].replace(/^\s+|\s+$/g, '');
				parts = candidate.split(/\s+/);
				url = parts[0];
				descriptor = parts.length > 1 ? parts[1] : '';
				width = /w$/.test(descriptor) ? parseInt(descriptor, 10) : 0;
				if (width > bestWidth && isUsableImageUrl(url)) {
					bestUrl = absoluteUrl(url);
					bestWidth = width;
				}
			}
			return bestUrl;
		}

		function inferFullSizeUrl(url) {
			if (!isUsableImageUrl(url)) {
				return '';
			}
			var parts = splitUrl(url);
			var inferred = parts.base.replace(thumbnailSizeRegex, '');
			if (inferred === parts.base) {
				return '';
			}
			return absoluteUrl(inferred + parts.query + parts.hash);
		}

		function getFullSizeUrl($img) {
			var sourceUrl = getSourceUrl($img);
			var explicitUrl = getFirstUsableAttribute($img, [
				'data-full-url',
				'data-full',
				'data-orig-file',
				'data-large-file'
			]);
			var srcsetUrl = getLargestSrcsetUrl($img.attr('srcset') || $img.attr('data-srcset'));
			var inferredUrl = inferFullSizeUrl(sourceUrl);

			if (explicitUrl && (!sourceUrl || !sameUrl(explicitUrl, sourceUrl))) {
				return explicitUrl;
			}
			if (srcsetUrl && (!sourceUrl || !sameUrl(srcsetUrl, sourceUrl))) {
				return srcsetUrl;
			}
			if (inferredUrl && (!sourceUrl || !sameUrl(inferredUrl, sourceUrl))) {
				return inferredUrl;
			}

			return '';
		}

		$(imageSelector).each(function () {
			var $img = $(this);
			var $target;
			var href;
			var title;

			if ($img.hasClass('size-full') || $img.closest('a, button, .fbx-link').length) {
				return;
			}
			if ($img.is(excludedSelector) || $img.closest(excludedSelector).length) {
				return;
			}

			href = getFullSizeUrl($img);
			if (!href) {
				return;
			}

			$target = $img.parent('picture');
			if (!$target.length) {
				$target = $img;
			}
			if ($target.parent('a, button').length) {
				return;
			}

			title = $img.attr('title') || $img.attr('alt') || '';
			$target.wrap($('<a/>', {
				'class': 'foobox foobox-auto-link',
				'href': href,
				'title': title,
				'data-foobox-auto-link': '1'
			}));
		});
	};
})(window, document);
