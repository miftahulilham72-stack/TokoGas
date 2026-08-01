@extends('layouts.app')

@section('title', 'Pembelian Baru')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-arrow-down"></i> Pembelian Baru</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pembelian.store') }}" method="POST" id="formPembelian">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="agen_id" class="form-label">Agen <span class="text-danger">*</span></label>
                        <select class="form-select @error('agen_id') is-invalid @enderror" 
                                id="agen_id" name="agen_id" required>
                            <option value="">Pilih Agen</option>
                            @foreach($agens as $agen)
                                <option value="{{ $agen->id }}" {{ old('agen_id') == $agen->id ? 'selected' : '' }}>
                                    {{ $agen->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('agen_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_beli" class="form-label">Tanggal Beli <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_beli') is-invalid @enderror" 
                               id="tanggal_beli" name="tanggal_beli" value="{{ old('tanggal_beli', date('Y-m-d')) }}" required>
                        @error('tanggal_beli')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="no_faktur" class="form-label">No Faktur</label>
                <input type="text" class="form-control @error('no_faktur') is-invalid @enderror" 
                       id="no_faktur" name="no_faktur" value="{{ old('no_faktur') }}">
                @error('no_faktur')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <hr>
            <h5>Detail Pembelian</h5>
            
            <div id="items-container">
                <div class="item-row row mb-2">
                    <div class="col-md-4">
                        <select class="form-select jenis-gas" name="items[0][jenis_gas_id]" required>
                            <option value="">Pilih Jenis Gas</option>
                            @foreach($jenisGases as $gas)
                                <option value="{{ $gas->id }}">{{ $gas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control jumlah" name="items[0][jumlah]" 
                               placeholder="Jumlah" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control harga-beli" name="items[0][harga_beli]" 
                               placeholder="Harga Beli" min="0" step="500" required>
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
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Pembelian
                </button>
                <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let itemIndex = 1;
    
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
    $(document).on('keyup change', '.jumlah, .harga-beli', function() {
        const row = $(this).closest('.item-row');
        const jumlah = row.find('.jumlah').val() || 0;
        const harga = row.find('.harga-beli').val() || 0;
        const subtotal = jumlah * harga;
        row.find('.subtotal').val('Rp ' + new Intl.NumberFormat('id-ID').format(subtotal));
        hitungTotal();
    });
    
    // Ambil harga agen otomatis
    $('#agen_id').change(function() {
        const agenId = $(this).val();
        if (!agenId) return;
        
        $('.jenis-gas').each(function() {
            const jenisGasId = $(this).val();
            if (jenisGasId) {
                getHargaAgen(agenId, jenisGasId, $(this));
            }
        });
    });
    
    $(document).on('change', '.jenis-gas', function() {
        const agenId = $('#agen_id').val();
        const jenisGasId = $(this).val();
        if (agenId && jenisGasId) {
            getHargaAgen(agenId, jenisGasId, $(this));
        }
    });
    
    function getHargaAgen(agenId, jenisGasId, element) {
        $.ajax({
            url: '{{ route("get.harga.agen") }}',
            type: 'GET',
            data: { agen_id: agenId, jenis_gas_id: jenisGasId },
            success: function(response) {
                const row = element.closest('.item-row');
                row.find('.harga-beli').val(response.harga);
                row.find('.harga-beli').trigger('keyup');
            }
        });
    }
    
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
@endpush
@endsection