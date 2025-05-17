<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-file-alt"></i> Pengajuan</h5>
        </div>
        <div class="card-body">
            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" action="<?= isset($pengajuanData) ? '/spk_ahp/pengajuan/edit/' . $pengajuanData['id'] : '/spk_ahp/pengajuan/add' ?>">
                <div class="form-group row mb-3">
                    <label for="nasabah_id" class="col-sm-3 col-form-label">Nasabah :</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="nasabah_id" name="nasabah_id" required>
                            <option value="">-- Pilih Nasabah --</option>
                            <?php while ($row = $nasabah->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?= $row['id'] ?>" <?= (isset($pengajuanData) && $pengajuanData['nasabah_id'] == $row['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['nama_lengkap']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="tanggal_pengajuan" class="col-sm-3 col-form-label">Tanggal :</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan" value="<?= isset($pengajuanData) ? htmlspecialchars($pengajuanData['tanggal_pengajuan']) : date('Y-m-d') ?>">
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="kondisi_ekonomi" class="col-sm-3 col-form-label">Kondisi Ekonomi :</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="kondisi_ekonomi" name="kondisi_ekonomi" required>
                            <option value="">-- Pilih Kondisi Ekonomi --</option>
                            <option value="Baik" <?= (isset($pengajuanData) && $pengajuanData['kondisi_ekonomi'] == 'Baik') ? 'selected' : '' ?>>Baik</option>
                            <option value="Cukup" <?= (isset($pengajuanData) && $pengajuanData['kondisi_ekonomi'] == 'Cukup') ? 'selected' : '' ?>>Cukup</option>
                            <option value="Buruk" <?= (isset($pengajuanData) && $pengajuanData['kondisi_ekonomi'] == 'Buruk') ? 'selected' : '' ?>>Buruk</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="karakter" class="col-sm-3 col-form-label">Karakter :</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="karakter" name="karakter" required>
                            <option value="">-- Pilih Karakter --</option>
                            <option value="Lancar" <?= (isset($pengajuanData) && $pengajuanData['karakter'] == 'Lancar') ? 'selected' : '' ?>>Lancar</option>
                            <option value="Kurang Lancar" <?= (isset($pengajuanData) && $pengajuanData['karakter'] == 'Kurang Lancar') ? 'selected' : '' ?>>Kurang Lancar</option>
                            <option value="Macet" <?= (isset($pengajuanData) && $pengajuanData['karakter'] == 'Macet') ? 'selected' : '' ?>>Macet</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="modal" class="col-sm-3 col-form-label">Modal :</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="modal" name="modal" value="<?= isset($pengajuanData) ? htmlspecialchars($pengajuanData['modal']) : '' ?>">
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="kemampuan" class="col-sm-3 col-form-label">Kemampuan :</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="kemampuan" name="kemampuan" value="<?= isset($pengajuanData) ? htmlspecialchars($pengajuanData['kemampuan']) : '' ?>">
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="jumlah_pinjaman" class="col-sm-3 col-form-label">Jumlah Uang :</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="jumlah_pinjaman" name="jumlah_pinjaman" value="<?= isset($pengajuanData) ? htmlspecialchars($pengajuanData['jumlah_pinjaman']) : '' ?>" required>
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="jangka_waktu" class="col-sm-3 col-form-label">Bulan :</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="jangka_waktu" name="jangka_waktu" value="<?= isset($pengajuanData) ? htmlspecialchars($pengajuanData['jangka_waktu']) : '' ?>" required>
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="jaminan" class="col-sm-3 col-form-label">Jaminan :</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="jaminan" name="jaminan" value="<?= isset($pengajuanData) ? htmlspecialchars($pengajuanData['jaminan']) : '' ?>">
                    </div>
                </div>
                
                <!-- Field yang tidak terlihat di UI tapi diperlukan untuk database -->
                <!-- Hapus baris-baris ini -->
                <!-- <input type="hidden" name="lama_usaha" value="<?= isset($pengajuanData) ? htmlspecialchars($pengajuanData['lama_usaha']) : '0' ?>"> -->
                <!-- <input type="hidden" name="jenis_usaha" value="<?= isset($pengajuanData) ? htmlspecialchars($pengajuanData['jenis_usaha']) : '' ?>"> -->
                <!-- <input type="hidden" name="tujuan_pinjaman" value="<?= isset($pengajuanData) ? htmlspecialchars($pengajuanData['tujuan_pinjaman']) : '' ?>"> -->
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-block w-100 mb-2">Simpan</button>
                    <a href="/spk_ahp/pengajuan" class="btn btn-secondary btn-block w-100">Hapus</a>
                </div>
            </form>
        </div>
    </div>
</div>