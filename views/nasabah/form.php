<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-<?= isset($nasabahData) ? 'edit' : 'plus-circle' ?>"></i> <?= isset($nasabahData) ? 'Edit' : 'Tambah' ?> Nasabah</h5>
            </div>
            <div class="card-body">
                <?php if (isset($error) && !empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <?php if (isset($success) && !empty($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                
                <form method="POST" action="<?= isset($nasabahData) ? '/spk_ahp/nasabah/edit/' . $nasabahData['id'] : '/spk_ahp/nasabah/add' ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_nasabah" class="form-label">Kode Nasabah <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_nasabah" name="kode_nasabah" value="<?= isset($nasabahData) ? htmlspecialchars($nasabahData['kode_nasabah']) : '' ?>" required maxlength="20">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= isset($nasabahData) ? htmlspecialchars($nasabahData['nama_lengkap']) : '' ?>" required maxlength="100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" value="<?= isset($nasabahData) ? htmlspecialchars($nasabahData['nik']) : '' ?>" maxlength="16">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelamin_l" value="Laki-laki" <?= (isset($nasabahData) && $nasabahData['jenis_kelamin'] == 'Laki-laki') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="jenis_kelamin_l">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelamin_p" value="Perempuan" <?= (isset($nasabahData) && $nasabahData['jenis_kelamin'] == 'Perempuan') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="jenis_kelamin_p">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?= isset($nasabahData) ? htmlspecialchars($nasabahData['tempat_lahir']) : '' ?>" maxlength="50">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= isset($nasabahData) ? htmlspecialchars($nasabahData['tanggal_lahir']) : '' ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= isset($nasabahData) ? htmlspecialchars($nasabahData['alamat']) : '' ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= isset($nasabahData) ? htmlspecialchars($nasabahData['no_telepon']) : '' ?>" maxlength="15">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="<?= isset($nasabahData) ? htmlspecialchars($nasabahData['pekerjaan']) : '' ?>" maxlength="100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="penghasilan_bulanan" class="form-label">Penghasilan Bulanan</label>
                                <input type="text" class="form-control" id="penghasilan_bulanan" name="penghasilan_bulanan" value="<?= isset($nasabahData) ? number_format($nasabahData['penghasilan_bulanan'], 0, ',', '.') : '' ?>" placeholder="Contoh: 500000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah_tanggungan" class="form-label">Jumlah Tanggungan</label>
                                <select class="form-select" id="jumlah_tanggungan" name="jumlah_tanggungan">
                                    <?php for ($i = 0; $i <= 10; $i++): ?>
                                        <option value="<?= $i ?>" <?= (isset($nasabahData) && $nasabahData['jumlah_tanggungan'] == $i) ? 'selected' : '' ?>>
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_pernikahan" class="form-label">Status Pernikahan</label>
                                <select class="form-select" id="status_pernikahan" name="status_pernikahan">
                                    <option value="Belum Menikah" <?= (isset($nasabahData) && $nasabahData['status_pernikahan'] == 'Belum Menikah') ? 'selected' : '' ?>>Belum Menikah</option>
                                    <option value="Menikah" <?= (isset($nasabahData) && $nasabahData['status_pernikahan'] == 'Menikah') ? 'selected' : '' ?>>Menikah</option>
                                    <option value="Cerai" <?= (isset($nasabahData) && $nasabahData['status_pernikahan'] == 'Cerai') ? 'selected' : '' ?>>Cerai</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tambahkan sebelum tombol submit di bagian bawah form -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <?php if (isset($nasabahData)): ?>
                                <button type="button" id="enableEditBtn" class="btn btn-warning"><i class="fas fa-unlock"></i> Aktifkan Mode Edit</button>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= isset($nasabahData) ? 'Update' : 'Simpan' ?></button>
                            <a href="/spk_ahp/nasabah" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inisialisasi nilai hidden input saat halaman dimuat
    let initialValue = $('#penghasilan_bulanan_display').val().replace(/\D/g, '');
    $('#penghasilan_bulanan').val(initialValue);
    
    // Format penghasilan bulanan dengan pemisah ribuan
    $('#penghasilan_bulanan_display').on('input', function() {
        // Hapus semua karakter non-digit
        let value = $(this).val().replace(/\D/g, '');
        
        // Simpan nilai tanpa pemisah ke hidden input
        $('#penghasilan_bulanan').val(value);
        
        // Format dengan pemisah ribuan untuk display
        if (value) {
            $(this).val(new Intl.NumberFormat('id-ID').format(value));
        }
    });
    
    // Pastikan form tidak disubmit jika penghasilan kosong
    $('form').on('submit', function() {
        // Jika penghasilan display diisi tapi hidden kosong
        if ($('#penghasilan_bulanan_display').val() && !$('#penghasilan_bulanan').val()) {
            // Ambil nilai dari display dan bersihkan
            let value = $('#penghasilan_bulanan_display').val().replace(/\D/g, '');
            $('#penghasilan_bulanan').val(value);
        }
        return true;
    });
    
    // Jika halaman edit, nonaktifkan semua input secara default
    <?php if (isset($nasabahData)): ?>
    // Nonaktifkan semua input kecuali tombol
    $('input, select, textarea').not(':submit, :button, :reset').prop('disabled', true);
    
    // Fungsi untuk mengaktifkan mode edit
    $('#enableEditBtn').on('click', function() {
        // Aktifkan semua input
        $('input, select, textarea').prop('disabled', false);
        
        // Ubah teks tombol
        $(this).html('<i class="fas fa-lock-open"></i> Mode Edit Aktif');
        $(this).removeClass('btn-warning').addClass('btn-success');
        $(this).prop('disabled', true);
        
        // Fokus ke input pertama
        $('#kode_nasabah').focus();
        
        // Tampilkan pesan
        $('<div class="alert alert-info mt-3">Mode edit aktif. Anda dapat mengubah semua data nasabah.</div>')
            .insertAfter($(this).parent());
    });
    <?php endif; ?>
});
</script>