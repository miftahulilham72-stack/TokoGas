@extends('layouts.app')

@section('title', 'Penjualan Baru')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-arrow-up"></i> Penjualan Baru</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('penjualan.store') }}" method="POST" id="formPenjualan">
            @csrf
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="tipe_pelanggan" class="form-label">Tipe Pelanggan <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipe_pelanggan') is-invalid @enderror" 
                                id="tipe_pelanggan" name="tipe_pelanggan" required>
                            <option value="toko" {{ old('tipe_pelanggan') == 'toko' ? 'selected' : '' }}>Toko / Warung</option>
                            <option value="rumahan" {{ old('tipe_pelanggan') == 'rumahan' ? 'selected' : '' }}>Rumahan</option>
                        </select>
                        @error('tipe_pelanggan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="tanggal_jual" class="form-label">Tanggal Jual <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_jual') is-invalid @enderror" 
                               id="tanggal_jual" name="tanggal_jual" value="{{ old('tanggal_jual', date('Y-m-d')) }}" required>
                        @error('tanggal_jual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status_pembayaran" class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('status_pembayaran') is-invalid @enderror" 
                                id="status_pembayaran" name="status_pembayaran" required>
                            <option value="lunas" {{ old('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="hutang" {{ old('status_pembayaran') == 'hutang' ? 'selected' : '' }}>Hutang</option>
                        </select>
                        @error('status_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div id="hutang-fields" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_pelanggan" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                                   id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}">
                            @error('nama_pelanggan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jatuh_tempo" class="form-label">Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('jatuh_tempo') is-invalid @enderror" 
                                   id="jatuh_tempo" name="jatuh_tempo" value="{{ old('jatuh_tempo', date('Y-m-d', strtotime('+7 days'))) }}">
                            @error('jatuh_tempo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            <h5>Detail Penjualan</h5>
            
            <div id="items-container">
                <div class="item-row row mb-2">
                    <div class="col-md-4">
                        <select class="form-select jenis-gas" name="items[0][jenis_gas_id]" required>
                            <option value="">Pilih Jenis Gas</option>
                            @foreach($jenisGases as $gas)
                                <option value="{{ $gas->id }}">{{ $gas->nama }} (Stok: {{ $gas->stok_sekarang }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control jumlah" name="items[0][jumlah]" 
                               placeholder="Jumlah" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control harga-jual" name="items[0][harga_jual]" 
                               placeholder="Harga Jual" min="0" step="500" required>
                        <small class="text-muted">Bisa diubah manual</small>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control subtotal" readonly placeholder="Subtotal">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-item">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <button type="button" class="btn btn-sm btn-secondary" id="add-item">
                    <i class="fas fa-plus"></i> Tambah Item
                </button>
            </div>
            
            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Harga</label>
                        <input type="text" class="form-control form-control-lg fw-bold" 
                               id="total_harga_display" value="Rp 0" readonly>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Penjualan
                </button>
                <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    let itemIndex = 1;
    
    // Tampilkan/hide field hutang
    $('#status_pembayaran').change(function() {
        if ($(this).val() == 'hutang') {
            $('#hutang-fields').show();
        } else {
            $('#hutang-fields').hide();
        }
    });
    $('#status_pembayaran').trigger('change');
    
    // Tambah item
    $('#add-item').click(function() {
        const newRow = $('.item-row:first').clone();
        newRow.find('select, input').val('');
        newRow.find('select, input').each(function() {
            const name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + itemIndex + ']'));
            }
        });
        newRow.find('.subtotal').val('');
        $('#items-container').append(newRow);
        itemIndex++;
    });
    
    // Hapus item
    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            hitungTotal();
        }
    });
    
    // Hitung subtotal
    $(document).on('keyup change', '.jumlah, .harga-jual', function() {
        const row = $(this).closest('.item-row');
        const jumlah = row.find('.jumlah').val() || 0;
        const harga = row.find('.harga-jual').val() || 0;
        const subtotal = jumlah * harga;
        row.find('.subtotal').val('Rp ' + new Intl.NumberFormat('id-ID').format(subtotal));
        hitungTotal();
    });
    
    // Ambil harga jual otomatis
    $(document).on('change', '.jenis-gas', function() {
        const tipe = $('#tipe_pelanggan').val();
        const jenisGasId = $(this).val();
        if (tipe && jenisGasId) {
            $.ajax({
                url: '{{ route("get.harga.jual") }}',
                type: 'GET',
                data: { jenis_gas_id: jenisGasId, tipe_pelanggan: tipe },
                success: function(response) {
                    const row = $(this).closest('.item-row');
                    row.find('.harga-jual').val(response.harga);
                    row.find('.harga-jual').trigger('keyup');
                }.bind(this)
            });
        }
    });
    
    // Update harga saat tipe pelanggan berubah
    $('#tipe_pelanggan').change(function() {
        $('.jenis-gas').each(function() {
            $(this).trigger('change');
        });
    });
    
    function hitungTotal() {
        let total = 0;
        $('.item-row').each(function() {
            const subtotalText = $(this).find('.subtotal').val();
            if (subtotalText) {
                const angka = parseInt(subtotalText.replace(/[^0-9]/g, ''));
                if (!isNaN(angka)) total += angka;
            }
        });
        $('#total_harga_display').val('Rp ' + new Intl.NumberFormat('id-ID').format(total));
    }
});
</script>
@endsection
@endsection