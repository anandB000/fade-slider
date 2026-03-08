# AJAX Security Fix - Testing Guide

## Issue Fixed
The plugin was showing "Security check failed" when trying to add new slides.

## Root Cause
The AJAX nonce verification was using `wp_verify_nonce()` directly which can be problematic. Updated to use `check_ajax_referer()` which is the WordPress standard for AJAX operations.

## Changes Made

### 1. **admin/fadeslider_admin.php** (AJAX Function)
- Changed from manual `wp_verify_nonce()` to `check_ajax_referer()` 
- `check_ajax_referer()` automatically handles security dies with proper error messages
- Added hook parameter to `fadeslider_adminscripts()` to ensure proper page targeting

### 2. **admin/fadeslider_admin.php** (Nonce Creation)
- Nonce name: `'fadeslider_nonce'`
- Properly localized to JavaScript via `wp_localize_script()`
- Only enqueued on fade_slider post type pages

### 3. **admin/js/fadeslider-admin_js.js**
- Both AJAX calls now include: `nonce: ajax_var.nonce`
- Added `.fail()` error handlers to both AJAX calls
- Error messages now display in the UI for debugging

## How It Works Now

1. **Nonce Creation**: When admin page loads, `wp_create_nonce('fadeslider_nonce')` creates a secure token
2. **Nonce Passing**: JavaScript sends nonce in AJAX POST data as `nonce: ajax_var.nonce`
3. **Nonce Verification**: Server verifies with `check_ajax_referer('fadeslider_nonce', 'nonce')`
4. **Error Handling**: If verification fails, clear error message is shown in browser console and UI

## Testing Steps

1. **Go to** WordPress Admin → Fade Slider
2. **Click** "Add Slide" button
3. **Select** images from media library
4. **Verify**: New slides appear in the table (no security error)
5. **Check** Browser Console (F12) for any error messages

## If Still Getting Error

Try these steps:

### Step 1: Clear Browser Cache
- Press `Ctrl+Shift+Delete` (or Cmd+Shift+Delete on Mac)
- Clear cache for the WordPress admin area
- Refresh the page

### Step 2: Check Browser Console
1. Press `F12` to open Developer Tools
2. Go to **Console** tab
3. Try adding a slide
4. Look for error messages that start with "AJAX Error:"
5. Share the error message for debugging

### Step 3: Verify Nonce is Being Sent
1. Open Developer Tools (F12)
2. Go to **Network** tab
3. Click "Add Slide" and select an image
4. In Network tab, click on the POST request to `admin-ajax.php`
5. Go to **Payload** tab
6. Verify you see: `nonce: (some long hash string)`

## Debugging Output

If there's still an issue, you'll now see error details:
- In **Browser Console**: Error message with details
- In **UI**: Red text showing the error message
- In **Server Logs**: Additional information if needed

## Files Modified
- `/admin/fadeslider_admin.php` - AJAX verification and nonce creation
- `/admin/js/fadeslider-admin_js.js` - AJAX calls with nonce and error handlers

## Expected Behavior After Fix

✅ Slides add without security errors
✅ Delete slide button works
✅ Page refreshes show slides were saved
✅ No JavaScript errors in console
✅ No "Security check failed" messages
