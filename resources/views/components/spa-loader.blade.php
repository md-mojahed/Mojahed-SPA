@if($type === 'spinner')

    <div class="spa-loader-spinner d-flex justify-content-center align-items-center py-4">
        <div class="spinner-border text-secondary" role="status" style="width:2rem; height:2rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

@elseif($type === 'skeleton')

    <div class="spa-loader-skeleton px-3 py-2">
        @for($i = 0; $i < $rows; $i++)
            <div class="d-flex gap-2 mb-2 align-items-center">
                <div class="spa-skeleton rounded" style="width:30px; height:{{ $height }};"></div>
                <div class="spa-skeleton rounded flex-grow-1" style="height:{{ $height }};"></div>
                <div class="spa-skeleton rounded" style="width:80px; height:{{ $height }};"></div>
            </div>
        @endfor
    </div>

@elseif($type === 'table')

    <div class="spa-loader-table">
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    @for($c = 0; $c < $cols; $c++)
                        <th><div class="spa-skeleton rounded" style="height:{{ $height }};"></div></th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @for($r = 0; $r < $rows; $r++)
                    <tr>
                        @for($c = 0; $c < $cols; $c++)
                            <td><div class="spa-skeleton rounded" style="height:{{ $height }};"></div></td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

@elseif($type === 'card')

    <div class="spa-loader-card p-3">
        <div class="spa-skeleton rounded mb-3" style="height:120px;"></div>
        @for($i = 0; $i < $rows; $i++)
            <div class="spa-skeleton rounded mb-2" style="height:{{ $height }}; width:{{ $i % 2 === 0 ? '100%' : '70%' }};"></div>
        @endfor
    </div>

@endif
