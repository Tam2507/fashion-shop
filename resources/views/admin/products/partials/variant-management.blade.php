<div class="p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Danh sach Variants ({{ $product->variants->count() }})</h6>
        <button type="button" class="btn btn-primary btn-sm" onclick="addVariantRow()">+ Them variant</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle" id="variantTable">
            <thead class="table-dark">
                <tr>
                    <th>Mau sac</th>
                    <th>Kich thuoc</th>
                    <th>Gia (d)</th>
                    <th>Ton kho</th>
                    <th style="width:120px">Thao tac</th>
                </tr>
            </thead>
            <tbody id="variantTbody">
                @foreach($product->variants as $v)
                <tr id="vrow-{{ $v->id }}" data-id="{{ $v->id }}">
                    <td><span class="view-cell">{{ $v->color ?: '-' }}</span><input class="form-control form-control-sm edit-cell d-none" value="{{ $v->color }}"></td>
                    <td>
                        <span class="view-cell">{{ $v->size ?: '-' }}</span>
                        <select class="form-select form-select-sm edit-cell d-none">
                            <option value="">-</option>
                            @foreach(['XS','S','M','L','XL','XXL','XXXL','38','39','40','41','42','43','44'] as $s)
                                <option value="{{ $s }}" {{ $v->size == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><span class="view-cell">{{ number_format($v->price,0,',','.') }}</span><input type="number" class="form-control form-control-sm edit-cell d-none" value="{{ $v->price }}"></td>
                    <td><span class="view-cell"><span class="badge {{ $v->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">{{ $v->stock_quantity }}</span></span><input type="number" class="form-control form-control-sm edit-cell d-none" value="{{ $v->stock_quantity }}"></td>
                    <td>
                        <button class="btn btn-xs btn-edit" onclick="startEdit(this)" title="Sua" style="padding:2px 8px;font-size:12px;background:#5B9BD5;color:white;border:none;border-radius:4px;cursor:pointer;">&#9998;</button>
                        <button class="btn btn-xs btn-save d-none" onclick="saveRow(this)" title="Luu" style="padding:2px 8px;font-size:12px;background:#28a745;color:white;border:none;border-radius:4px;cursor:pointer;">&#10003;</button>
                        <button class="btn btn-xs btn-cancel d-none" onclick="cancelEdit(this)" title="Huy" style="padding:2px 8px;font-size:12px;background:#6c757d;color:white;border:none;border-radius:4px;cursor:pointer;">&#10005;</button>
                        <button class="btn btn-sm btn-delete" onclick="deleteVariant({{ $v->id }})">Xoa</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
var productId = {{ $product->id }};
var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function startEdit(btn) {
    var row = btn.closest('tr');
    row.querySelectorAll('.view-cell').forEach(function(el){ el.classList.add('d-none'); });
    row.querySelectorAll('.edit-cell').forEach(function(el){ el.classList.remove('d-none'); });
    row.querySelector('.btn-edit').classList.add('d-none');
    row.querySelector('.btn-save').classList.remove('d-none');
    row.querySelector('.btn-cancel').classList.remove('d-none');
    row.querySelector('.btn-delete').classList.add('d-none');
}

function cancelEdit(btn) {
    var row = btn.closest('tr');
    row.querySelectorAll('.view-cell').forEach(function(el){ el.classList.remove('d-none'); });
    row.querySelectorAll('.edit-cell').forEach(function(el){ el.classList.add('d-none'); });
    row.querySelector('.btn-edit').classList.remove('d-none');
    row.querySelector('.btn-save').classList.add('d-none');
    row.querySelector('.btn-cancel').classList.add('d-none');
    row.querySelector('.btn-delete').classList.remove('d-none');
}

function saveRow(btn) {
    var row = btn.closest('tr');
    var id = row.dataset.id;
    var cells = row.querySelectorAll('.edit-cell');
    var color = cells[0].value.trim();
    var size = cells[1].value;
    var price = cells[2].value;
    var stock = cells[3].value;
    var sku = 'PRD' + productId + '-' + (color ? color.toUpperCase().substring(0,3) : 'NA') + '-' + (size || 'OS');

    fetch('/admin/products/' + productId + '/variants/' + id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify({ sku: sku, color: color, size: size, price: price, stock_quantity: stock })
    }).then(function(r){ return r.json(); }).then(function(d){
        if (d.success) { location.reload(); }
        else { alert(d.message || 'Loi!'); }
    }).catch(function(){ alert('Loi ket noi!'); });
}

function deleteVariant(id) {
    if (!confirm('Xoa variant nay?')) return;
    fetch('/admin/products/' + productId + '/variants/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    }).then(function(r){ return r.json(); }).then(function(d){
        if (d.success) { var row = document.getElementById('vrow-' + id); if (row) row.remove(); }
        else { alert(d.message || 'Loi!'); }
    });
}

function addVariantRow() {
    var sizes = ['','XS','S','M','L','XL','XXL','XXXL','38','39','40','41','42','43','44'];
    var opts = sizes.map(function(s){ return '<option value="'+s+'">'+(s||'- Chon -')+'</option>'; }).join('');
    var html = '<tr class="new-row">'
        + '<td><input class="form-control form-control-sm" id="newColor" placeholder="mau sac"></td>'
        + '<td><select class="form-select form-select-sm" id="newSize">'+opts+'</select></td>'
        + '<td><input type="number" class="form-control form-control-sm" id="newPrice" value="{{ $product->price }}"></td>'
        + '<td><input type="number" class="form-control form-control-sm" id="newStock" value="0"></td>'
        + '<td>'
        + '<button class="btn btn-sm btn-success me-1" onclick="saveNewRow()">Luu</button>'
        + '<button class="btn btn-sm btn-secondary" onclick="this.closest(\'tr\').remove()">Huy</button>'
        + '</td></tr>';
    var tbody = document.getElementById('variantTbody');
    var existing = tbody.querySelector('.new-row');
    if (existing) existing.remove();
    tbody.insertAdjacentHTML('beforeend', html);
}

function saveNewRow() {
    var color = document.getElementById('newColor').value.trim();
    var size = document.getElementById('newSize').value;
    var price = document.getElementById('newPrice').value;
    var stock = document.getElementById('newStock').value;
    if (!color && !size) { alert('Nhap mau hoac size!'); return; }
    var sku = 'PRD' + productId + '-' + (color ? color.toUpperCase().substring(0,3) : 'NA') + '-' + (size || 'OS');
    fetch('/admin/products/' + productId + '/variants', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify({ sku: sku, color: color, size: size, price: price, stock_quantity: stock })
    }).then(function(r){ return r.json(); }).then(function(d){
        if (d.success) { location.reload(); }
        else { alert(d.message || 'Loi!'); }
    }).catch(function(){ alert('Loi ket noi!'); });
}
</script>