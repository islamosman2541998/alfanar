@props(['colspan' => 10, 'text' => null])

<tr>
    <td colspan="{{ $colspan }}">
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            {{ $text ?? __('No records found') }}
        </div>
    </td>
</tr>
