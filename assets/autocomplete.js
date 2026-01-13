(function () {
  const DEBOUNCE_MS = 250;
  const MIN_LENGTH = 1;

  function debounce(fn, ms) {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), ms);
    };
  }

  function createDropdown() {
    const el = document.createElement('div');
    el.className = 'autocomplete-dropdown list-group shadow-sm';
    el.style.position = 'absolute';
    el.style.zIndex = 2000;
    el.style.display = 'none';
    document.body.appendChild(el);
    return el;
  }

  function positionDropdown(dropdown, inputEl) {
    const rect = inputEl.getBoundingClientRect();
    dropdown.style.minWidth = Math.max(rect.width, 200) + 'px';
    dropdown.style.left = rect.left + window.scrollX + 'px';
    dropdown.style.top = rect.bottom + window.scrollY + 'px';
  }

  function openDropdown(dropdown) {
    dropdown.style.display = 'block';
  }

  function closeDropdown(dropdown) {
    dropdown.style.display = 'none';
    dropdown.innerHTML = '';
  }

  function renderItems(dropdown, items) {
    dropdown.innerHTML = '';
    if (!items || items.length === 0) {
      const empty = document.createElement('div');
      empty.className = 'list-group-item small text-muted';
      empty.textContent = 'No suggestions';
      dropdown.appendChild(empty);
      return;
    }

    items.forEach((it, idx) => {
      const a = document.createElement('a');
      a.className = 'list-group-item list-group-item-action autocomplete-item';
      a.href = '#';
      a.dataset.index = idx;
      a.dataset.url = it.url || '';
      a.dataset.name = it.name;
      a.dataset.id = it.id || '';
      a.textContent = it.name;
      dropdown.appendChild(a);
    });
  }

  function attachAutocompleteTo(inputEl) {
    const suggestUrl = inputEl.dataset.suggestUrl || '/search/suggest';
    const dropdown = createDropdown();
    let items = [];
    let active = -1;

    const doFetch = debounce(async () => {
      const q = inputEl.value.trim();
      if (q.length < MIN_LENGTH) {
        closeDropdown(dropdown);
        return;
      }

      const url = new URL(suggestUrl, window.location.origin);
      url.searchParams.set('q', q);

      try {
        const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
        if (!res.ok) {
          closeDropdown(dropdown);
          return;
        }
        items = await res.json();
        if (!Array.isArray(items)) items = [];
        renderItems(dropdown, items);
        positionDropdown(dropdown, inputEl);
        openDropdown(dropdown);
        active = -1;
      } catch (e) {
        // network ...
        closeDropdown(dropdown);
      }
    }, DEBOUNCE_MS);

    inputEl.addEventListener('input', doFetch);

    inputEl.addEventListener('keydown', (ev) => {
      if (dropdown.style.display === 'none') return;

      if (ev.key === 'ArrowDown' || ev.key === 'ArrowUp') {
        const nodes = dropdown.querySelectorAll('.autocomplete-item');
        if (!nodes.length) return;
        ev.preventDefault();
        if (ev.key === 'ArrowDown') {
          active = Math.min(active + 1, nodes.length - 1);
        } else {
          active = Math.max(active - 1, 0);
        }
        nodes.forEach((n, i) => n.classList.toggle('active', i === active));
      } else if (ev.key === 'Enter') {
        ev.preventDefault();
        const nodes = dropdown.querySelectorAll('.autocomplete-item');
        if (active >= 0 && nodes[active]) {
          const node = nodes[active];
          const url = node.dataset.url;
          if (url) {
            window.location.href = url;
          } else {
            inputEl.value = node.dataset.name || '';
            closeDropdown(dropdown);
          }
        } else {
          const form = inputEl.closest('form');
          if (form) form.submit();
        }
      } else if (ev.key === 'Escape') {
        closeDropdown(dropdown);
      }
    });

    document.addEventListener('click', (e) => {
      if (e.target.closest && e.target.closest('.autocomplete-dropdown')) {
        const a = e.target.closest('.autocomplete-item');
        if (a) {
          e.preventDefault();
          const url = a.dataset.url;
          if (url) {
            window.location.href = url;
          } else {
            inputEl.value = a.dataset.name || '';
            closeDropdown(dropdown);
          }
        }
        return;
      }
      if (e.target !== inputEl) {
        closeDropdown(dropdown);
      }
    });

    window.addEventListener('resize', () => {
      if (dropdown.style.display !== 'none') positionDropdown(dropdown, inputEl);
    });

    window.addEventListener('scroll', () => {
      if (dropdown.style.display !== 'none') positionDropdown(dropdown, inputEl);
    }, { passive: true });
  }

  document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('input[data-autocomplete="product"]');
    inputs.forEach(attachAutocompleteTo);
  });
})();
