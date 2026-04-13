<div class="p-3">
    @if($product->variants->count() > 0)
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr><th>SKU</th><th>Mau</th><th>Size</th><th>Gia</th><th>Ton kho</th><th>Thao tac</th></tr>
            </thead>
            <tbody>
                @foreach($product->variants as $variant)
                <tr id="variant-row-{{ $variant->id }}">
                    <td><code>{{ $variant->sku }}</code></td>
                    <td>{{ $variant->color ?: '-' }}</td>
                    <td>{{ $variant->size ?: '-' }}</td>
                    <td>{{ number_format($variant->price, 0, ',', '.') }}</td>
                    <td><span class="badge {{ $variant->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">{{ $variant->stock_quantity }}</span></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="editVariant({{ $variant->id }}, '{{ addslashes($variant->sku) }}', '{{ addslashes($variant->color) }}', '{{ $variant->size }}', {{ $variant->price }}, {{ $variant->stock_quantity }})">Sua</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteVariant({{ $variant->id }})">Xoa</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-info mb-4">Chua co variant nao.</div>
    @endif
    <div class="card border-0 bg-light"><div class="card-body">
        <h6 class="fw-bold mb-3" id="variantFormTitle">Them Variant Moi</h6>
        <input type="hidden" id="editingVariantId" value="">
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fw-bold small">Mau sac</label><input type="text" id="vColor" class="form-control" placeholder="do, xanh, trang"></div>
            <div class="col-md-4"><label class="form-label fw-bold small">Kich thuoc</label>
                <select id="vSize" class="form-select"><option value="">-- Chon --</option>
                @foreach(['XS','S','M','L','XL','XXL','XXXL','38','39','40','41','42','43','44'] as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label fw-bold small">Gia</label><input type="number" id="vPrice" class="form-control" value="{{ $product->price }}" min="0"></div>
            <div class="col-md-4"><label class="form-label fw-bold small">Ton kho</label><input type="number" id="vStock" class="form-control" value="0" min="0"></div>
            <div class="col-md-8 d-flex align-items-end gap-2">
                <button type="button" class="btn btn-primary" onclick="saveVariant()"><span id="variantSaveBtnText">Them</span></button>
                <button type="button" class="btn btn-secondary" onclick="cancelEditVariant()" id="cancelVariantBtn" style="display:none;">Huy</button>
            </div>
        </div>
    </div></div>
</div>
<script>
var productId={{ $product->id }};
var csrf=document.querySelector('meta[name="csrf-token"]').getAttribute('content');
function saveVariant(){
    var id=document.getElementById('editingVariantId').value;
    var color=document.getElementById('vColor').value.trim();
    var size=document.getElementById('vSize').value;
    var price=document.getElementById('vPrice').value;
    var stock=document.getElementById('vStock').value;
    if(!color&&!size){alert('Nhap mau hoac size!');return;}
    var sku='PRD'+productId+'-'+(color?color.toUpperCase().substring(0,3):'NA')+'-'+(size||'OS');
    var url=id?'/admin/products/'+productId+'/variants/'+id:'/admin/products/'+productId+'/variants';
    fetch(url,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:JSON.stringify({sku:sku,color:color,size:size,price:price,stock_quantity:stock})})
    .then(function(r){return r.json();}).then(function(d){if(d.success){location.reload();}else{alert(d.message||'Loi!');}}).catch(function(){alert('Loi ket noi!');});
}
function editVariant(id,sku,color,size,price,stock){
    document.getElementById('editingVariantId').value=id;
    document.getElementById('vColor').value=color;
    document.getElementById('vSize').value=size;
    document.getElementById('vPrice').value=price;
    document.getElementById('vStock').value=stock;
    document.getElementById('variantFormTitle').textContent='Chinh Sua Variant';
    document.getElementById('variantSaveBtnText').textContent='Luu';
    document.getElementById('cancelVariantBtn').style.display='inline-block';
}
function cancelEditVariant(){
    document.getElementById('editingVariantId').value='';
    document.getElementById('vColor').value='';
    document.getElementById('vSize').value='';
    document.getElementById('vPrice').value={{ $product->price }};
    document.getElementById('vStock').value=0;
    document.getElementById('variantFormTitle').textContent='Them Variant Moi';
    document.getElementById('variantSaveBtnText').textContent='Them';
    document.getElementById('cancelVariantBtn').style.display='none';
}
function deleteVariant(id){
    if(!confirm('Xoa?'))return;
    fetch('/admin/products/'+productId+'/variants/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}})
    .then(function(r){return r.json();}).then(function(d){if(d.success){var r=document.getElementById('variant-row-'+id);if(r)r.remove();}else{alert(d.message||'Loi!');}});
}
</script>