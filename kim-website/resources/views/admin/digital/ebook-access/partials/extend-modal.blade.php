<!-- Modal Perpanjang Akses -->
<div class="modal-overlay" id="extendModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Perpanjang Akses E-book</h2>
            <button type="button" class="modal-close" onclick="closeExtendModal()">&times;</button>
        </div>

        <form method="POST" id="extendForm">
            @csrf
            <div class="modal-body">
                <p style="margin-bottom: 20px;">
                    E-book: <strong id="ebookName"></strong>
                </p>

                <div class="form-group">
                    <label for="days">
                        Tambah Durasi (Hari) <span class="required">*</span>
                    </label>
                    <input type="number" id="days" name="days" class="form-control" min="1" max="365" value="30"
                        required>
                </div>

                <!-- Preset -->
                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
                    <button type="button" class="btn-preset" onclick="setExtendDays(30)">+1 Bulan</button>
                    <button type="button" class="btn-preset" onclick="setExtendDays(60)">+2 Bulan</button>
                    <button type="button" class="btn-preset" onclick="setExtendDays(90)">+3 Bulan</button>
                    <button type="button" class="btn-preset" onclick="setExtendDays(180)">+6 Bulan</button>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeExtendModal()">Batal</button>
                <button type="submit" class="btn-primary">Perpanjang Akses</button>
            </div>
        </form>
    </div>
</div>

<script>
function showExtendModal(accessId, ebookName) {
    document.getElementById('ebookName').textContent = ebookName;
    document.getElementById('extendForm').action =
        `/admin/digital/ebook-access/${accessId}/extend`;
    document.getElementById('extendModal').classList.add('show');
}

function closeExtendModal() {
    document.getElementById('extendModal').classList.remove('show');
}

function setExtendDays(days) {
    document.getElementById('days').value = days;
}
</script>