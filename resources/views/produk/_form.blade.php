@csrf
@if (!empty($produk->foto))
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
        class="form-control @error('foto') is-invalid @enderror">
        @error('foto')
            <div id="validationServerFotoFeedback" class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col">
        <div class="mb-2">
            <label for="preview">preview Foto</label> <br>
            <img id="preview" class="img-thumbnail mt-2" style="display: none;" width="150">
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
        const file = input.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }
</script>