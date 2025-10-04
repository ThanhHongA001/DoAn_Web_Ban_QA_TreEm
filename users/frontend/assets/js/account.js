document.addEventListener('DOMContentLoaded', () => {
  const alertBox = document.getElementById('alert');
  const viewMode = document.getElementById('view-mode');
  const editForm = document.getElementById('edit-form');

  const vName = document.getElementById('v-name');
  const vEmail = document.getElementById('v-email');
  const vPhone = document.getElementById('v-phone');
  const vAddress = document.getElementById('v-address');

  const iName = document.getElementById('name');
  const iEmail = document.getElementById('email');
  const iPhone = document.getElementById('phone');
  const iAddress = document.getElementById('address');
  const iCurrentPassword = document.getElementById('current_password');

  const btnEdit = document.getElementById('btn-edit');
  const btnCancel = document.getElementById('btn-cancel');

  // Mask helpers
  function maskName(str = '') {
    if (!str) return '—';
    return str.length <= 3 ? str : `${str.slice(0,3)}****${str.slice(-1)}`;
  }
  function maskEmail(str = '') {
    if (!str) return '—';
    const at = str.indexOf('@');
    if (at < 0) return maskName(str);
    const local = str.slice(0, at);
    const domain = str.slice(at);
    const head = local.slice(0, Math.min(3, local.length));
    return `${head}****${domain}`;
  }
  function maskPhone(str = '') {
    if (!str) return '—';
    const digits = str.replace(/\D/g, '');
    if (digits.length < 7) return `${digits.slice(0,3)}****`;
    return `${digits.slice(0,3)}****${digits.slice(-3)}`;
  }
  function maskAddress(str = '') {
    if (!str) return '—';
    // Che số nhà và một phần đầu
    const parts = str.split(',').map(s => s.trim());
    if (parts.length === 0) return '—';
    const first = parts[0];
    const maskedFirst = first.length <= 4 ? '****' : `${first.slice(0,2)}****`;
    return [maskedFirst, ...parts.slice(1)].join(', ');
  }

  function showAlert(type, msg) {
    alertBox.className = 'mb-4 p-3 rounded-lg';
    alertBox.textContent = msg;
    if (type === 'success') {
      alertBox.classList.add('bg-green-50','text-green-700','border','border-green-200');
    } else if (type === 'error') {
      alertBox.classList.add('bg-red-50','text-red-700','border','border-red-200');
    } else {
      alertBox.classList.add('bg-blue-50','text-blue-700','border','border-blue-200');
    }
    alertBox.classList.remove('hidden');
  }
  function hideAlert() {
    alertBox.classList.add('hidden');
  }

  // Load profile
  async function loadProfile() {
    hideAlert();
    try {
      const res = await fetch('api/auth/get_profile.php', { credentials: 'same-origin' });
      const data = await res.json();
      if (!data.isLoggedIn) {
        window.location.href = 'login.html';
        return;
      }
      // Fill masked
      vName.textContent = maskName(data.user.name);
      vEmail.textContent = maskEmail(data.user.email);
      vPhone.textContent = maskPhone(data.user.phone);
      vAddress.textContent = maskAddress(data.user.address);

      // Fill edit inputs (dữ liệu thật)
      iName.value = data.user.name || '';
      iEmail.value = data.user.email || '';
      iPhone.value = data.user.phone || '';
      iAddress.value = data.user.address || '';
    } catch (e) {
      showAlert('error', 'Không tải được thông tin. Vui lòng thử lại.');
    }
  }

  btnEdit.addEventListener('click', () => {
    viewMode.classList.add('hidden');
    editForm.classList.remove('hidden');
    iCurrentPassword.value = '';
    hideAlert();
  });

  btnCancel.addEventListener('click', () => {
    editForm.classList.add('hidden');
    viewMode.classList.remove('hidden');
    hideAlert();
  });

  editForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    hideAlert();

    const payload = {
      name: iName.value.trim(),
      email: iEmail.value.trim(),
      phone: iPhone.value.trim(),
      address: iAddress.value.trim(),
      current_password: iCurrentPassword.value
    };

    // Front-end validate đơn giản
    if (!payload.current_password) {
      showAlert('error', 'Vui lòng nhập mật khẩu hiện tại để xác thực.');
      return;
    }
    if (payload.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(payload.email)) {
      showAlert('error', 'Email không hợp lệ.');
      return;
    }
    if (payload.phone && !/^(0|\+84)\d{8,11}$/.test(payload.phone.replace(/\s/g,''))) {
      showAlert('error', 'Số điện thoại không hợp lệ.');
      return;
    }

    try {
      const res = await fetch('api/auth/update_profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      if (data.status === 'success') {
        showAlert('success', 'Cập nhật thành công.');
        editForm.classList.add('hidden');
        viewMode.classList.remove('hidden');
        await loadProfile();
      } else {
        showAlert('error', data.message || 'Không thể cập nhật.');
      }
    } catch (err) {
      showAlert('error', 'Lỗi kết nối. Vui lòng thử lại.');
    }
  });

  loadProfile();
});
