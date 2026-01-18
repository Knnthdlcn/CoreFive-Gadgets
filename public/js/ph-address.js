// Philippines address cascading dropdown helper
// Depends on the app exposing API endpoints:
//  - GET /api/ph/regions
//  - GET /api/ph/regions/{regionCode}/provinces
//  - GET /api/ph/provinces/{provinceCode}/cities
//  - GET /api/ph/cities/{cityCode}/barangays

(function () {
  async function fetchJson(url) {
    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    if (!res.ok) throw new Error('Request failed: ' + res.status);
    return res.json();
  }

  function el(selector) {
    return selector ? document.querySelector(selector) : null;
  }

  function setOptions(selectEl, items, placeholder) {
    if (!selectEl) return;
    const current = selectEl.value;

    selectEl.innerHTML = '';
    const ph = document.createElement('option');
    ph.value = '';
    ph.textContent = placeholder;
    selectEl.appendChild(ph);

    (items || []).forEach((item) => {
      const opt = document.createElement('option');
      opt.value = item.code;
      opt.textContent = item.name;
      opt.dataset.name = item.name;
      selectEl.appendChild(opt);
    });

    // try preserve current selection if still available
    if (current) {
      const match = Array.from(selectEl.options).find((o) => o.value === current);
      if (match) selectEl.value = current;
    }
  }

  function getSelectedName(selectEl) {
    if (!selectEl) return '';
    const opt = selectEl.options[selectEl.selectedIndex];
    return opt && opt.dataset ? opt.dataset.name || opt.textContent || '' : '';
  }

  function composeAddress({ street, barangay, city, province, region, postal }) {
    const parts = [street, barangay, city, province, region].filter(Boolean);
    let out = parts.join(', ');
    if (postal) out += (out ? ' ' : '') + postal;
    return out;
  }

  async function initSelector(config) {
    const regionSelect = el(config.regionSelect);
    const provinceSelect = el(config.provinceSelect);
    const citySelect = el(config.citySelect);
    const barangaySelect = el(config.barangaySelect);
    const streetInput = el(config.streetInput);
    const postalInput = el(config.postalInput);
    const previewTextarea = el(config.previewTextarea);

    const onAnyChange = typeof config.onAnyChange === 'function' ? config.onAnyChange : () => {};
    const initial = config.initial || {};

    const updatePreview = () => {
      if (!previewTextarea) return;
      const address = composeAddress({
        street: streetInput ? streetInput.value.trim() : '',
        barangay: getSelectedName(barangaySelect),
        city: getSelectedName(citySelect),
        province: getSelectedName(provinceSelect),
        region: getSelectedName(regionSelect),
        postal: postalInput ? postalInput.value.trim() : '',
      });
      if (address) previewTextarea.value = address;
    };

    const resetSelect = (selectEl, placeholder) => {
      if (!selectEl) return;
      selectEl.innerHTML = '';
      const ph = document.createElement('option');
      ph.value = '';
      ph.textContent = placeholder;
      selectEl.appendChild(ph);
      selectEl.value = '';
      selectEl.disabled = true;
    };

    // load regions
    const regions = await fetchJson('/api/ph/regions');
    setOptions(regionSelect, regions, 'Select region');

    const loadProvinces = async (regionCode) => {
      resetSelect(provinceSelect, 'Select province');
      resetSelect(citySelect, 'Select city/municipality');
      resetSelect(barangaySelect, 'Select barangay');
      if (!regionCode) {
        updatePreview();
        return;
      }
      const provinces = await fetchJson('/api/ph/regions/' + encodeURIComponent(regionCode) + '/provinces');
      setOptions(provinceSelect, provinces, 'Select province');
      provinceSelect.disabled = false;
    };

    const loadCities = async (provinceCode) => {
      resetSelect(citySelect, 'Select city/municipality');
      resetSelect(barangaySelect, 'Select barangay');
      if (!provinceCode) {
        updatePreview();
        return;
      }
      const cities = await fetchJson('/api/ph/provinces/' + encodeURIComponent(provinceCode) + '/cities');
      setOptions(citySelect, cities, 'Select city/municipality');
      citySelect.disabled = false;
    };

    const loadBarangays = async (cityCode) => {
      resetSelect(barangaySelect, 'Select barangay');
      if (!cityCode) {
        updatePreview();
        return;
      }
      const barangays = await fetchJson('/api/ph/cities/' + encodeURIComponent(cityCode) + '/barangays');
      setOptions(barangaySelect, barangays, 'Select barangay');
      barangaySelect.disabled = false;
    };

    regionSelect?.addEventListener('change', async () => {
      onAnyChange();
      await loadProvinces(regionSelect.value);
      updatePreview();
    });

    provinceSelect?.addEventListener('change', async () => {
      onAnyChange();
      await loadCities(provinceSelect.value);
      updatePreview();
    });

    citySelect?.addEventListener('change', async () => {
      onAnyChange();
      await loadBarangays(citySelect.value);
      updatePreview();
    });

    barangaySelect?.addEventListener('change', () => {
      onAnyChange();
      updatePreview();
    });

    streetInput?.addEventListener('input', () => {
      onAnyChange();
      updatePreview();
    });

    postalInput?.addEventListener('input', () => {
      onAnyChange();
      updatePreview();
    });

    // Apply initial selections (if any)
    if (initial.region) {
      regionSelect.value = initial.region;
      await loadProvinces(initial.region);
    }
    if (initial.province) {
      provinceSelect.value = initial.province;
      await loadCities(initial.province);
    }
    if (initial.city) {
      citySelect.value = initial.city;
      await loadBarangays(initial.city);
    }
    if (initial.barangay) {
      barangaySelect.value = initial.barangay;
      barangaySelect.disabled = false;
    }

    // enable any already-populated selects
    if (regionSelect && regionSelect.value) regionSelect.disabled = false;
    if (provinceSelect && provinceSelect.options.length > 1) provinceSelect.disabled = false;
    if (citySelect && citySelect.options.length > 1) citySelect.disabled = false;
    if (barangaySelect && barangaySelect.options.length > 1) barangaySelect.disabled = false;

    updatePreview();
  }

  window.PHAddress = {
    initSelector,
  };
})();
