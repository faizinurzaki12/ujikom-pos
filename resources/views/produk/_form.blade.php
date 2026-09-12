@csrf

@if (session('foto_compressed_size') && !empty($produk->foto))
    <div class="col-md-12 mb-3">
        <div class="card" style="max-width: 250px;">
            <img src="{{ asset('storage/' . $produk->foto) }}"
                 class="card-img-top img-thumbnail"
                 alt="Foto produk">
            <div class="card-body d-flex gap-2 justify-content-center p-2">
                <span class="badge bg-secondary">
                    {{ number_format(session('foto_original_size') / 1048576, 2) }} MB
                </span>
                <span class="badge bg-success">
                    {{ number_format(session('foto_compressed_size') / 1024, 0) }} KB (Max 70 KB)
                </span>
            </div>
        </div>
    </div>
@endif

@if (!empty($produk->foto) && !session('foto_compressed_size'))
    <div class="mb-2">
        <label>Foto Saat Ini</label><br>
        <img src="{{ asset('storage/' . $produk->foto) }}"
             width="150"
             class="img-thumbnail">
    </div>
@endif

<!-- untuk upload gambar -->
<div class="col-md-12">
    <label for="validationServerFoto" class="form-label">Gambar</label>
    <input type="file" 
           name="foto" 
           onchange="previewImage(this)"
           id="validationServerFoto" 
           class="form-control @error('foto') is-invalid @enderror"
           accept="image/*">
    @error('foto')
        <div id="validationServerFotoFeedback" class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="col">
    <div class="mb-2">
        <label for="preview">Preview Foto</label> <br>
        <img id="preview" class="img-thumbnail mt-2" style="display: none;" width="150">
        <div id="previewSizeInfo" class="small text-muted mt-1"></div>
    </div>
</div>

<!-- untuk nama produknya -->
<div class="col-md-12">
    <label for="validationServerName" class="form-label">Nama Produk</label>
    <input type="text" name="name" id="validationServerName" 
           class="form-control @error('name') is-invalid @enderror" 
           value="{{ old('name' , $produk->nama ?? '') }}">
    @error('name')
        <div id="validationServerNameFeedback" class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<!-- select jenis produk -->
<div class="col-md-12">
    <label for="validationServerJenis" class="form-label">Jenis Produk</label>
    <select name="jenis_id" id="validationServerJenis"
            class="form-select @error('jenis_id') is-invalid @enderror">
        <option value="" disabled {{ old('jenis_id', $produk->jenis_id ?? '') ? '' : 'selected' }}>
            -- Pilih Jenis --
        </option>
        @foreach($jenisList as $jenis)
            <option value="{{ $jenis->id }}"
                {{ (int) old('jenis_id', $produk->jenis_id ?? '') === $jenis->id ? 'selected' : '' }}>
                {{ $jenis->nama_jenis }}
            </option>
        @endforeach
    </select>
    @error('jenis_id')
        <div id="validationServerJenisFeedback" class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>

<!-- Harga Beli -->
<div class="col-md-12">
    <label for="validationServerPurchasePrice" class="form-label">Harga Beli</label>
    <input type="number" name="purchase_price" id="validationServerPurchasePrice" 
           class="form-control @error('purchase_price') is-invalid @enderror" 
           value="{{ old('purchase_price' , $produk->harga_beli ?? '') }}"> 
    @error('purchase_price')
        <div id="validationServerPurchasePriceFeedback" class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<!-- Harga jual -->
<div class="col-md-12">
    <label for="validationServerSellingPrice" class="form-label">Harga Jual</label>
    <input type="number" name="selling_price" id="validationServerSellingPrice" 
           class="form-control @error('selling_price') is-invalid @enderror" 
           value="{{ old('selling_price' , $produk->harga_jual ?? '') }}">
    @error('selling_price')
        <div id="validationServerSellingPriceFeedback" class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<!-- stok -->
<div class="col-md-12">
    <label for="validationServerStock" class="form-label">Stok</label>
    <input type="number" name="stock" id="validationServerStock" 
           class="form-control @error('stock') is-invalid @enderror" 
           value="{{ old('stock' , $produk->stok ?? '') }}">
    @error('stock')
        <div id="validationServerStockFeedback" class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<!-- Tombol Aksi -->
<div class="col-12 mt-4">
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const sizeInfo = document.getElementById('previewSizeInfo');
        const file = input.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';

            const sizeMb = (file.size / 1048576).toFixed(2);
            const sizeKb = (file.size / 1024).toFixed(0);

            sizeInfo.innerHTML = `
                <span class="badge bg-secondary me-1">${sizeMb} MB</span> ->
                <span class="badge bg-success">${sizeKb} KB</span>
            `;
        } else {
            preview.style.display = 'none';
            sizeInfo.innerHTML = '';
        }
    }
</script>