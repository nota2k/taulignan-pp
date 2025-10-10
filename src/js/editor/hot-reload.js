/**
 * Handle hot-reload in the admin customizer
 */

if ( import.meta?.hot ) {
	document.addEventListener('DOMContentLoaded', () => {
		const editor = document.getElementById('site-editor');

		if ( ! editor ) {
			return;
		}

		const observer = new MutationObserver(() => {
			if ( ! window.frames[ "editor-canvas" ] ) {
				return;
			}

			import.meta.hot.on("vite:afterUpdate", () => window.frames[ "editor-canvas" ].location.reload() );

			observer.disconnect();
		});

		observer.observe( editor, { childList: true, subtree: true, attributes: false });
	});
}
