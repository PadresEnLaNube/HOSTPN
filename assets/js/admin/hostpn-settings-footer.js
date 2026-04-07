(function () {
  'use strict';
  var saveBtn = document.getElementById('hostpn-settings-save');
  var exportBtn = document.getElementById('hostpn-settings-export');
  var importBtn = document.getElementById('hostpn-settings-import');
  var fileInput = document.getElementById('hostpn-settings-import-file');
  if (!saveBtn) return;

  var menuToggle = document.getElementById('wp-admin-bar-menu-toggle');
  var footer = document.getElementById('hostpn-settings-footer');
  if (menuToggle && footer) {
    menuToggle.addEventListener('click', function () {
      setTimeout(function () {
        footer.style.display = document.body.classList.contains('wp-responsive-open') ? 'none' : '';
      }, 0);
    });
  }

  saveBtn.addEventListener('click', function () {
    var form = document.getElementById('hostpn_form');
    if (form) form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
  });

  exportBtn.addEventListener('click', function () {
    var fd = new FormData();
    fd.append('action', 'hostpn_ajax');
    fd.append('hostpn_ajax_type', 'hostpn_settings_export');
    fd.append('hostpn_ajax_nonce', hostpnSettingsFooter.nonce);
    fetch(hostpnSettingsFooter.ajaxUrl, { method: 'POST', body: fd })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res.error_key) { if (typeof hostpn_get_main_message === 'function') hostpn_get_main_message(hostpnSettingsFooter.i18n.exportError, 'red'); return; }
        var blob = new Blob([JSON.stringify(res.settings, null, 2)], { type: 'application/json' });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'hostpn-settings-' + new Date().toISOString().slice(0, 10) + '.json';
        document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
      })
      .catch(function () { if (typeof hostpn_get_main_message === 'function') hostpn_get_main_message(hostpnSettingsFooter.i18n.exportError, 'red'); });
  });

  importBtn.addEventListener('click', function () { fileInput.value = ''; fileInput.click(); });

  fileInput.addEventListener('change', function () {
    var file = fileInput.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
      var data;
      try { data = JSON.parse(e.target.result); } catch (err) { if (typeof hostpn_get_main_message === 'function') hostpn_get_main_message(hostpnSettingsFooter.i18n.invalidFile, 'red'); return; }
      if (!confirm(hostpnSettingsFooter.i18n.confirmImport)) return;
      var fd = new FormData();
      fd.append('action', 'hostpn_ajax');
      fd.append('hostpn_ajax_type', 'hostpn_settings_import');
      fd.append('hostpn_ajax_nonce', hostpnSettingsFooter.nonce);
      fd.append('settings', JSON.stringify(data));
      fetch(hostpnSettingsFooter.ajaxUrl, { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res.error_key) { if (typeof hostpn_get_main_message === 'function') hostpn_get_main_message(res.error_content || hostpnSettingsFooter.i18n.importError, 'red'); return; }
          if (typeof hostpn_get_main_message === 'function') hostpn_get_main_message(hostpnSettingsFooter.i18n.importSuccess, 'green');
          setTimeout(function () { location.reload(); }, 1500);
        })
        .catch(function () { if (typeof hostpn_get_main_message === 'function') hostpn_get_main_message(hostpnSettingsFooter.i18n.importError, 'red'); });
    };
    reader.readAsText(file);
  });

  // Page creation buttons
  var createBtns = document.querySelectorAll('.hostpn-create-page-btn');
  createBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var pageType = btn.getAttribute('data-hostpn-page-type');
      btn.disabled = true;
      btn.textContent = hostpnSettingsFooter.i18n.creatingPage || 'Creating page...';

      var fd = new FormData();
      fd.append('action', 'hostpn_ajax');
      fd.append('hostpn_ajax_type', 'hostpn_create_page');
      fd.append('hostpn_ajax_nonce', hostpnSettingsFooter.nonce);
      fd.append('hostpn_page_type', pageType);

      fetch(hostpnSettingsFooter.ajaxUrl, { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res.error_key === '' && res.redirect_url) {
            window.location.href = res.redirect_url;
          } else {
            btn.disabled = false;
            btn.textContent = hostpnSettingsFooter.i18n.createPage || 'Create page';
            if (typeof hostpn_get_main_message === 'function') {
              hostpn_get_main_message(res.error_content || hostpnSettingsFooter.i18n.errorCreatingPage, 'red');
            }
          }
        })
        .catch(function () {
          btn.disabled = false;
          btn.textContent = hostpnSettingsFooter.i18n.createPage || 'Create page';
          if (typeof hostpn_get_main_message === 'function') {
            hostpn_get_main_message(hostpnSettingsFooter.i18n.errorCreatingPage, 'red');
          }
        });
    });
  });
})();
