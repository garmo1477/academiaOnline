<tr class="text-center">
    <td>{{ $order->id }}</td>
    <td>{{ $order->formatted_total_amount }}</td>
    <td>{{ $order->coupon_code }}</td>
    <td>{{ $order->created_at->format('d/m/Y') }}</td>
    <td>{{ $order->formatted_status }}</td>
    <td>{{ $order->order_lines_count }}</td>
    
    @if(!$detail)
        <td>
            <a href="{{ route('student.orders.show', ['order' => $order]) }}" class="btn btn-outline-dark">
                <i class="fa fa-eye">{{ __('Ver detalle') }}</i>
            </a>
        </td>
    @endif
</tr>