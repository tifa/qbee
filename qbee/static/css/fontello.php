<?php require_once '../../_/css.header.php'; ?>
@font-face {
	font-family: 'fontello';
	src: url('/static/fonts/fontello.eot?');
	src: url('/static/fonts/fontello.eot?#iefix') format('embedded-opentype'),
		url('/static/fonts/fontello.woff?') format('woff'),
		url('/static/fonts/fontello.ttf?') format('truetype'),
		url('/static/fonts/fontello.svg?#fontello') format('svg');
	font-weight: normal;
	font-style: normal;
}
/* Chrome hack: SVG is rendered more smooth in Windozze. 100% magic, uncomment if you need it. */
/* Note, that will break hinting! In other OS-es font will be not as sharp as it could be */
/*
@media screen and (-webkit-min-device-pixel-ratio:0) {
	@font-face {
		font-family: 'fontello';
		src: url('/static/fonts/fontello.svg?#fontello') format('svg');
	}
}
*/

[class^="icon-"]:before,
[class*=" icon-"]:before {
	font-family: "fontello";
	font-style: normal;
	font-weight: normal;
	speak: none;

	display: inline-block;
	text-decoration: inherit;
	width: 1em;
	margin-right: .2em;
	text-align: center;
	/* opacity: .8; */

	/* For safety - reset parent styles, that can break glyph codes*/
	font-variant: normal;
	text-transform: none;

	/* fix buttons height, for twitter bootstrap */
	line-height: 1em;

	/* Animation center compensation - margins should be symmetric */
	/* remove if not needed */
	margin-left: .2em;

	/* you can be more comfortable with increased icons size */
	/* font-size: 120%; */

	/* Uncomment for 3D effect */
	/* text-shadow: 1px 1px 1px rgba(127, 127, 127, 0.3); */
}

/*
   Animation example, for spinners
*/
i.spin {
	-moz-animation: spin 2s infinite linear;
	-o-animation: spin 2s infinite linear;
	-webkit-animation: spin 2s infinite linear;
	animation: spin 2s infinite linear;
	display: inline-block;
}
@-moz-keyframes spin {
	0% {
		-moz-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		-webkit-transform: rotate(0deg);
		transform: rotate(0deg);
	}

	100% {
		-moz-transform: rotate(359deg);
		-o-transform: rotate(359deg);
		-webkit-transform: rotate(359deg);
		transform: rotate(359deg);
	}
}
@-webkit-keyframes spin {
	0% {
		-moz-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		-webkit-transform: rotate(0deg);
		transform: rotate(0deg);
	}

	100% {
		-moz-transform: rotate(359deg);
		-o-transform: rotate(359deg);
		-webkit-transform: rotate(359deg);
		transform: rotate(359deg);
	}
}
@-o-keyframes spin {
	0% {
		-moz-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		-webkit-transform: rotate(0deg);
		transform: rotate(0deg);
	}

	100% {
		-moz-transform: rotate(359deg);
		-o-transform: rotate(359deg);
		-webkit-transform: rotate(359deg);
		transform: rotate(359deg);
	}
}
@-ms-keyframes spin {
	0% {
		-moz-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		-webkit-transform: rotate(0deg);
		transform: rotate(0deg);
	}

	100% {
		-moz-transform: rotate(359deg);
		-o-transform: rotate(359deg);
		-webkit-transform: rotate(359deg);
		transform: rotate(359deg);
	}
}
@keyframes spin {
	0% {
		-moz-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		-webkit-transform: rotate(0deg);
		transform: rotate(0deg);
	}

	100% {
		-moz-transform: rotate(359deg);
		-o-transform: rotate(359deg);
		-webkit-transform: rotate(359deg);
		transform: rotate(359deg);
	}
}

/*
spinner		animation.php
			illustrations.php
			static/css/lightbox.php
			static/js/lightbox.php
deviantart	static/footer.php
			app/blogger/icons.php
cancel		static/css/panel.php
goodreads	static/footer.php
rss			static/footer.php
shop		static/panel.header.php

*/

.icon-cancel:before { content: '\e800'; } /* 'î €' */
.icon-illustrations:before { content: '\e801'; } /* 'î ' */
.icon-down:before { content: '\e802'; } /* 'î ‚' */
.icon-up:before { content: '\e803'; } /* 'î ƒ' */
.icon-food:before { content: '\e804'; } /* 'î „' */
.icon-star:before { content: '\e805'; } /* 'î …' */
.icon-qbee:before { content: '\e806'; } /* 'î †' */
.icon-star-empty:before { content: '\e807'; } /* 'î ‡' */
.icon-blog:before { content: '\e808'; } /* 'î ˆ' */
.icon-edit:before { content: '\e809'; } /* 'î ‰' */
.icon-shop:before { content: '\e80a'; } /* 'î Š' */
.icon-words:before { content: '\e80b'; } /* 'î ‹' */
.icon-animation:before { content: '\e80c'; } /* 'î Œ' */
.icon-sketches:before { content: '\e80d'; } /* 'î ' */
.icon-publications:before { content: '\e80e'; } /* 'î Ž' */
.icon-instagram:before { content: '\e80f'; } /* 'î ' */
.icon-deviantart:before { content: '\e810'; } /* 'î ' */
.icon-twitter:before { content: '\e811'; } /* 'î ‘' */
.icon-rss:before { content: '\e812'; } /* 'î ’' */
.icon-goodreads:before { content: '\e813'; } /* 'î “' */
.icon-spinner:before { content: '\e814'; } /* 'î ”' */
.icon-email:before { content: '\e815'; } /* 'î •' */
.icon-tag:before { content: '\e816'; } /* 'î –' */
.icon-view:before { content: '\e817'; } /* 'î —' */

<?php require_once '../../_/css.footer.php';
