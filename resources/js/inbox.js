document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // select all
  const selectAll = document.getElementById('selectAll');
  if (selectAll) {
    selectAll.addEventListener('change', function () {
      document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
    });
  }

  // row click (use inbox-row class)
  document.querySelectorAll('.inbox-row').forEach(row => {
    row.addEventListener('click', function (e) {
      if (e.target.closest('input') || e.target.closest('button') || e.target.closest('a')) return;
      const link = row.querySelector('a.subject');
      if (link) window.location = link.href;
    });
  });

  // toggle star/important
  window.toggleFlag = function (id, type, btn) {
    fetch(`/inbox/${id}/toggle-${type}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    }).then(r => r.json()).then(data => {
      const icon = btn.querySelector('i');
      if (type === 'star') {
        icon.classList.toggle('bi-star-fill', data.active);
        icon.classList.toggle('bi-star', !data.active);
        btn.classList.toggle('text-warning', data.active);
      } else {
        icon.classList.toggle('bi-bookmark-fill', data.active);
        icon.classList.toggle('bi-bookmark', !data.active);
        btn.classList.toggle('text-danger', data.active);
      }
    }).catch(err => {
      console.warn('toggle error', err);
    });
  }

  // bulk action submit
  window.submitBulkAction = function (action) {
    const checked = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(i => i.value);
    if (checked.length === 0) { alert('Please select at least one message.'); return; }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/inbox/bulk-action';
    form.style.display = 'none';

    const token = document.createElement('input'); token.name = '_token'; token.value = csrfToken; form.appendChild(token);
    const act = document.createElement('input'); act.name = 'action'; act.value = action; form.appendChild(act);

    checked.forEach(id => {
      const el = document.createElement('input'); el.name = 'ids[]'; el.value = id; form.appendChild(el);
    });

    document.body.appendChild(form); form.submit();
  }

  // delete with confirm
  window.confirmDelete = function(id){
    if (!confirm('Are you sure you want to delete the selected message?')) return;
    fetch(`/inbox/delete/${id}`, {method:'POST', headers:{'X-CSRF-TOKEN':csrfToken}}).then(()=> location.reload());
  }

});
