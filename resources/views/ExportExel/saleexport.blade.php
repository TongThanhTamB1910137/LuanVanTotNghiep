<table class="table">
    <thead>
        <tr>
            <th>id</th>
            <th>ngay_ban</th>
            <th>id_don_hang</th>
            <th>tien_don_hang</th>
        </tr>
    </thead>

    <tbody>
        <?php $moneyResult = 0;
        ?>
        @foreach ($saleStatis as $val)
            <tr>
                <td>{{ $val->id }}</td>
                <td><?php
                $date = date_create($val->ngay_ban);
                echo date_format($date, 'd/m/Y');
                ?>
                </td>
                <td>{{ $val->id_don_hang }}</td>
                <td>{{ $val->tien_don_hang }}</td>
                <?php
                 $moneyResult += $val->tien_don_hang;
                ?>
                
            </tr>
        @endforeach
    </tbody>
    <th>Tong tien ban dc:<?php echo $moneyResult; ?>
    </th>
</table>
