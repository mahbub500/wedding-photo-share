# Wedding Photo Share (WordPress Plugin)

Simple plugin that allows wedding guests to upload photos by scanning a QR code and to download the gallery.

## Features
- QR code link (generate QR externally) that opens a portal page.
- Portal shows two actions: **Upload Photo(s)** and **Download All Photos**.
- Guests can upload single or multiple images (no login required).
- Images are stored in `wp-content/uploads/wedding-photos/`.
- Gallery displays uploaded images and provides a per-image download link.
- "Download All" zips all images and sends a ZIP to the browser.

## Installation
1. Upload the `wedding-photo-share` folder to your WordPress plugins directory (`/wp-content/plugins/`).
2. Activate the plugin in the WordPress admin.
3. Create a new Page and insert the shortcode: `[wps_wedding_portal]`
4. Generate a QR code that points to the page URL you created and print it on cards.

## Notes & Security
- This plugin allows anonymous uploads. Consider adding a secret token or moderation step before events.
- Allowed image types: jpg, jpeg, png, gif, webp.
- If you want moderation, modify `wps_handle_upload` to store uploads in a holding folder and add an admin approval screen.

## Files
- `wedding-photo-share.php` — main plugin file
- `assets/css/style.css` — styles
- `assets/js/upload.js` — JS for upload/gallery refresh
- `readme.md` — this file

## License
MIT — feel free to modify and publish on GitHub.

