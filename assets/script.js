function initFormValidation() {
    const form = document.getElementById('formSiswa');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            validateField(this);
        });
        
        input.addEventListener('blur', function() {
            validateField(this);
        });
    });
}

function validateField(field) {
    let isValid = true;
    let errorMsg = '';
    
    if (field.name === 'nama') {
        if (field.value.trim().length < 2) {
            isValid = false;
            errorMsg = 'Nama minimal 2 karakter';
        } else if (field.value.trim().length > 100) {
            isValid = false;
            errorMsg = 'Nama maksimal 100 karakter';
        }
    } else if (field.name === 'nis') {
        if (!/^[0-9]*$/.test(field.value)) {
            isValid = false;
            errorMsg = 'NIS hanya boleh angka';
        } else if (field.value.trim().length > 20) {
            isValid = false;
            errorMsg = 'NIS maksimal 20 digit';
        }
    } else if (field.name === 'kelas') {
        if (field.value.trim().length > 50) {
            isValid = false;
            errorMsg = 'Kelas maksimal 50 karakter';
        }
    } else if (field.name === 'jurusan') {
        if (field.value.trim().length > 100) {
            isValid = false;
            errorMsg = 'Jurusan maksimal 100 karakter';
        }
    }
    
    if (field.value.trim() === '') {
        field.classList.remove('valid', 'invalid');
    } else if (isValid) {
        field.classList.remove('invalid');
        field.classList.add('valid');
    } else {
        field.classList.remove('valid');
        field.classList.add('invalid');
    }
    
    const hintEl = document.getElementById('err-' + field.name);
    if (hintEl) {
        hintEl.textContent = errorMsg;
    }
}

function confirmDelete(id, nama) {
    let modalOverlay = document.getElementById('deleteModal');
    
    if (!modalOverlay) {
        modalOverlay = document.createElement('div');
        modalOverlay.id = 'deleteModal';
        modalOverlay.className = 'modal-overlay';
        modalOverlay.innerHTML = `
            <div class="modal">
                <div class="modal-header">Konfirmasi Hapus</div>
                <div class="modal-body" id="modalBody">
                    Apakah Anda yakin ingin menghapus data siswa ini?
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                    <button class="btn btn-delete" id="confirmDeleteBtn">Hapus</button>
                </div>
            </div>
        `;
        document.body.appendChild(modalOverlay);
        
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                closeDeleteModal();
            }
        });
    }
    
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `Apakah Anda yakin ingin menghapus data siswa: <strong>${nama}</strong>?`;
    
    window.deleteId = id;
    
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.onclick = function() {
            executeDelete(window.deleteId);
        };
    }
    
    modalOverlay.classList.add('show');
}

function closeDeleteModal() {
    const modalOverlay = document.getElementById('deleteModal');
    if (modalOverlay) {
        modalOverlay.classList.remove('show');
    }
}

function executeDelete(id) {
    window.location.href = 'hapus.php?id=' + id;
}

document.addEventListener('DOMContentLoaded', function () {
    const alertMessage = document.getElementById('alertMessage');
    
    if (alertMessage) {
        setTimeout(function() {
            alertMessage.classList.add('fade-out');
            setTimeout(function() {
                alertMessage.remove();
            }, 300); 
        }, 5000);
    }
    
    const form = document.getElementById('formSiswa');
    const btnSubmit = document.getElementById('btnSubmit');
    
    if (form && btnSubmit) {
        form.addEventListener('submit', function(e) {
            const btnText = btnSubmit.querySelector('.btn-text');
            const btnLoading = btnSubmit.querySelector('.btn-loading');
            
            if (btnText && btnLoading) {
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline';
                btnSubmit.disabled = true;
            }
        });
    }
    
    if (form) {
        form.addEventListener('submit', function (e) {
            let isValid = true;

            const nama    = document.getElementById('nama');
            const nis     = document.getElementById('nis');
            const jk      = document.getElementById('jenis_kelamin');
            const kelas   = document.getElementById('kelas');
            const jurusan = document.getElementById('jurusan');

            const errNama    = document.getElementById('err-nama');
            const errNis     = document.getElementById('err-nis');
            const errJk      = document.getElementById('err-jk');
            const errKelas   = document.getElementById('err-kelas');
            const errJurusan = document.getElementById('err-jurusan');

            if (errNama)    errNama.textContent    = '';
            if (errNis)     errNis.textContent     = '';
            if (errJk)      errJk.textContent      = '';
            if (errKelas)   errKelas.textContent   = '';
            if (errJurusan) errJurusan.textContent = '';

            if (nama && nama.value.trim() === '') {
                if (errNama) errNama.textContent = 'Nama wajib diisi.';
                isValid = false;
            } else if (nama && (nama.value.trim().length < 2 || nama.value.trim().length > 100)) {
                if (errNama) errNama.textContent = 'Nama harus 2-100 karakter.';
                isValid = false;
            }

            if (nis && nis.value.trim() === '') {
                if (errNis) errNis.textContent = 'NIS wajib diisi.';
                isValid = false;
            } else if (nis && !/^[0-9]+$/.test(nis.value.trim())) {
                if (errNis) errNis.textContent = 'NIS harus berupa angka.';
                isValid = false;
            } else if (nis && nis.value.trim().length > 20) {
                if (errNis) errNis.textContent = 'NIS maksimal 20 digit.';
                isValid = false;
            }

            if (jk && jk.value.trim() === '') {
                if (errJk) errJk.textContent = 'Pilih jenis kelamin.';
                isValid = false;
            }

            if (kelas && kelas.value.trim() === '') {
                if (errKelas) errKelas.textContent = 'Kelas wajib diisi.';
                isValid = false;
            } else if (kelas && kelas.value.trim().length > 50) {
                if (errKelas) errKelas.textContent = 'Kelas maksimal 50 karakter.';
                isValid = false;
            }

            if (jurusan && jurusan.value.trim() === '') {
                if (errJurusan) errJurusan.textContent = 'Jurusan wajib diisi.';
                isValid = false;
            } else if (jurusan && jurusan.value.trim().length > 100) {
                if (errJurusan) errJurusan.textContent = 'Jurusan maksimal 100 karakter.';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                if (btnSubmit) {
                    const btnText = btnSubmit.querySelector('.btn-text');
                    const btnLoading = btnSubmit.querySelector('.btn-loading');
                    if (btnText && btnLoading) {
                        btnText.style.display = 'inline';
                        btnLoading.style.display = 'none';
                        btnSubmit.disabled = false;
                    }
                }
            }
        });
        
        const nis = document.getElementById('nis');
        if (nis) {
            nis.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    initFormValidation();
});
