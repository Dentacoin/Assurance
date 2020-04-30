<section class="container padding-top-50 padding-bottom-50 records-history module">
    <div class="row">
        <div class="col-xs-12 col-lg-10 col-lg-offset-1">
            <h2 class="fs-34 text-center lato-bold padding-bottom-20">Records History</h2>
            <table>
                <thead>
                <tr>
                    <th class="lato-bold first-th">Date</th>
                    <th class="lato-bold">Action</th>
                    <th class="lato-bold">Details</th>
                </tr>
                </thead>
                <tbody>
                @foreach($recordsHistory as $record)
                    <tr @if($record->type == 'teeth-cleaning' || $record->type == 'check-up') data-id="{{$record->id}}" @endif>
                        <td>
                            @if(!empty($record->request_date_at))
                                {{date('d/m/Y', strtotime($record->request_date_at))}}
                            @else
                                {{date('d/m/Y', strtotime($record->created_at))}}
                            @endif
                        </td>
                        <td>
                            @if($record->type == 'teeth-cleaning')
                                Teeth cleaning recorded
                            @elseif($record->type == 'check-up')
                                Check-up recorded
                            @else
                                {{$record->type}}
                            @endif
                        </td>
                        <td class="details">
                            @if($record->type == 'teeth-cleaning' || $record->type == 'check-up')
                                @if($record->status == 'approved')
                                    <span class="lato-bold active-color">CONFIRMED</span>
                                @elseif($record->status == 'rejected')
                                    <span class="lato-bold cancelled-color">DECLINED</span>
                                @elseif($record->status == 'sent')
                                    <span class="lato-bold">PENDING</span>
                                @endif
                            @elseif(!empty($record->data))
                                <a href="https://etherscan.io/tx/{{$record->data}}" target="_blank" class="blue-green-color lato-bold">SEE PROOF</a>
                            @else
                                <span class="lato-bold">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>