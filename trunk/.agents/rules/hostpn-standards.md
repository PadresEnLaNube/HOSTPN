# HostPN Standards & Design Components

Always use the standard HostPN components, classes, and helper functions in all features:

## 1. Forms & Inputs (`class-hostpn-forms.php` / `hostpn-forms.js`)
- Structural wrappers: `.hostpn-input-wrapper`
- Labels: `<label class="hostpn-label">`
- Inputs and Selects: `.hostpn-field.hostpn-input` or `.hostpn-field.hostpn-select`

## 2. Popups & Modals (`hostpn-popups.js` / `hostpn-popups.css`)
- Structure:
  ```html
  <div class="hostpn-popup-overlay"></div>
  <div id="popup-id" class="hostpn-popup hostpn-popup-size-medium">
    <div class="hostpn-popup-content">
      <div class="hostpn-popup-header">
        <h3 class="hostpn-popup-title">Title</h3>
        <button type="button" class="hostpn-popup-close">&times;</button>
      </div>
      <div class="hostpn-popup-body">...</div>
      <div class="hostpn-popup-footer">...</div>
    </div>
  </div>
  ```
- JS API: `window.HOSTPN_Popups.open('popup-id')` / `window.HOSTPN_Popups.close('popup-id')`

## 3. Buttons (`hostpn-btn`)
- Primary button: `<button class="hostpn-btn hostpn-btn-mini">`
- Transparent/Secondary button: `<button class="hostpn-btn hostpn-btn-mini hostpn-btn-transparent">`
- Toggle button: `<button class="hostpn-btn hostpn-btn-mini hostpn-btn-transparent hostpn-toggle">`

## 4. Tooltips (`hostpn-tooltips.js` / `hostpn-tooltips.css`)
- Class: `.hostpn-tooltip`
- Attribute: `title="Tooltip text..."`
- JS Initialization: `window.HOSTPN_Tooltips.init('.hostpn-tooltip')`

## 5. Financial & UI Accounting Standards
- Always pre-render financial HTML server-side from `HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accommodation_id)`.
- Use high-contrast light card backgrounds (`#ffffff`), dark slate text (`#0f172a`), and soft status pill badges (`#dcfce7`, `#fef9c3`, `#fee2e2`).
