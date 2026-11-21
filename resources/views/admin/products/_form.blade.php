@php($p = $product ?? null)

<label>Kategori
    <select name="category_id" required>
        <option value="">Pilih</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $p?->category_id) == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
</label>

<label>SKU
    <input class="input" name="sku" value="{{ old('sku', $p?->sku) }}" required>
</label>

<label>Nama Produk
    <input class="input" name="name" value="{{ old('name', $p?->name) }}" required>
</label>

<label>Deskripsi
    <textarea class="input" rows="5" name="description">{{ old('description', $p?->description) }}</textarea>
</label>

<label>Harga
    <input class="input" type="number" min="0" step="0.01" name="price" value="{{ old('price', $p?->price ?? 0) }}" required>
</label>

<label>Gambar
    @if($p?->image)
        <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->name }}" style="height:80px;display:block;margin:6px 0">
    @endif
    <input class="input" type="file" name="image" accept="image/*">
</label>

<label>
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $p?->is_active ?? true)) > Aktif
</label>

<button class="btn">Simpan</button>