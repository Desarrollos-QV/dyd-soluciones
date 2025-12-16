<div class="col-lg-10 mx-auto grid-margin stretch-card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                @yield('title')
            </h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="dispositivo">Dispositivo</label>
                            <input type="text" name="dispositivo" class="form-control mb-4 mb-md-0"
                                value="{{ $device->dispositivo ?? old('dispositivo') }}" required />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="marca">Marca</label>
                            <input type="text" name="marca" class="form-control mb-4 mb-md-0"
                                value="{{ $device->marca ?? old('marca') }}" required />
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="generacion">Generacion</label>
                            <input type="text" name="generacion" class="form-control mb-4 mb-md-0"
                                value="{{ $device->generacion ?? old('generacion') }}" required />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label>IMEIs Asignados</label>
                            <div id="imei_container">
                                @php
                                    $imeis = [];
                                    if (old('imei')) {
                                        $imeis = old('imei');
                                    } elseif (isset($device) && $device->imeis && $device->imeis->count() > 0) {
                                        $imeis = $device->imeis->pluck('imei')->toArray();
                                    } elseif (isset($device) && $device->imei) {
                                        $imeis = [$device->imei];
                                    }
                                    if (empty($imeis)) {
                                        $imeis = [''];
                                    }
                                @endphp

                                @foreach($imeis as $index => $imei)
                                    <div class="input-group mb-2 imei-row">
                                        <input type="text" name="imei[]" class="form-control" value="{{ $imei }}"
                                            placeholder="Ingrese IMEI" required>
                                        <button type="button" class="btn btn-danger btn-remove-imei"
                                            tabindex="-1">Quitar</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-success btn-sm mt-2" id="btn-add-imei">
                                Agregar IMEI
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="garantia">Garantia</label>
                            <input type="date" name="garantia" class="form-control"
                                value="{{ old('garantia', isset($device->garantia) ? \Carbon\Carbon::parse($device->garantia)->format('Y-m-d') : now()->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="ia">IA</label>
                            <select name="ia" id="ia" class="form-select mb-4">
                                <option value="si" @if($device->ia == 'si') selected @endif>SI</option>
                                <option value="no" @if($device->ia == 'no') selected @endif>NO</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="row">

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="stock">Stock del dispositivo</label>
                            <input type="number" name="stock" id="stock" class="form-control mb-4 mb-md-0"
                                value="{{ $device->stock ?? old('stock') ?? 0 }}" readonly />
                            <small class="text-muted">Calculado automáticamente</small>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="stock_min_alert">Stock Minima para alerta</label>
                            <input type="number" name="stock_min_alert" class="form-control mb-4 mb-md-0"
                                value="{{ $device->stock_min_alert ?? old('stock_min_alert') }}" required />
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="otra_empresa">¿Pertenencio a otra empresa?</label>
                            <select name="otra_empresa" id="otra_empresa" class="form-select mb-4">
                                <option value="si" @if($device->otra_empresa == 'si') selected @endif>SI</option>
                                <option value="no" @if($device->otra_empresa == 'no') selected @endif>NO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="observations">Observaciones <small class="text-gray">(Opcional)</small> </label>
                            <textarea name="observations" id="observations" class="form-control"
                                placeholder="Observaciones" cols="30" rows="10">{!! $device->observations !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit"
                            class="btn btn-primary">{{ isset($device->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('devices.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imeiContainer = document.getElementById('imei_container');
        const btnAddImei = document.getElementById('btn-add-imei');
        const stockInput = document.getElementById('stock');

        function updateStock() {
            const inputs = imeiContainer.querySelectorAll('input[name="imei[]"]');
            let count = 0;
            inputs.forEach(input => {
                if (input.value.trim() !== '') {
                    count++;
                }
            });
            // Update even if empty to show total fields? Or only filled? 
            // User requirement: "cuando agregemos un IMEI con javascript que se sume al stock"
            // Usually implies filled, but visually it might be better to count rows or filled rows.
            // Let's count all rows for now as they represent intended stock, or better, count rows.
            // But if empty, it's not really an IMEI. 
            // Let's count rows, but the backend only saves filled ones.
            // Actually, if we add a row, stock increases.
            stockInput.value = imeiContainer.querySelectorAll('.imei-row').length;
        }

        btnAddImei.addEventListener('click', function () {
            const div = document.createElement('div');
            div.className = 'input-group mb-2 imei-row';
            div.innerHTML = `
                <input type="text" name="imei[]" class="form-control" placeholder="Ingrese IMEI" required>
                <button type="button" class="btn btn-danger btn-remove-imei" tabindex="-1">Quitar</button>
            `;
            imeiContainer.appendChild(div);
            updateStock();
        });

        imeiContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-remove-imei') || e.target.closest('.btn-remove-imei')) {
                const row = e.target.closest('.imei-row');
                // Don't remove if it's the last one? User might want 0?
                // But generally 1 is required.
                if (imeiContainer.querySelectorAll('.imei-row').length > 1) {
                    row.remove();
                    updateStock();
                } else {
                    // Clear value instead
                    row.querySelector('input').value = '';
                    alert('Debe haber al menos un campo de IMEI. Si no hay stock, puede dejarlo vacío (si la validación lo permite) o eliminar el dispositivo.');
                }
            }
        });

        imeiContainer.addEventListener('input', function (e) {
            // If we want to count only filled fields, we would listen to input.
            // But simpler to count rows as "slots".
        });

        // Initial count
        updateStock();
    });
</script>