{{-- Carrito --}}
<h3>Carrito</h3>
<table class="table table-bordered" id="carrito">
    <thead>
        <tr>
            <th>Formulario</th>
            <th>Cantidad</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($contentCarritoFormularios))
            @foreach($contentCarritoFormularios as $cartItem)
                <tr>
                    <td>{{ $cartItem->name }}</td>
                    <td>{{ $cartItem->qty }}</td>
                    <td>
                        <form action="{{ route('formularios.removeItem', $cartItem->rowId) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
