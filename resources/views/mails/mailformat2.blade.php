<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $mailReceiverName }}</title>
  </head>
  <body class="">

    <p>Dear <strong>{{ $mailReceiverName }}</strong>,</p>    

    <p>Please find the visitor details report below.</p>
    <br/>
    {{-- width: 100%; border-collapse: collapse; border: 1px solid black; --}}
      @if ($bodyMessage->data->count())
        <table style="border-collapse: collapse; border: 1px solid black;">
          <thead>
            <th style="border: 1px solid black; padding: 10px;">Serial</th>
            <th style="border: 1px solid black; padding: 10px;">Date</th>
            <th  style="border: 1px solid black; padding: 10px;">Vendor</th>
            <th  style="border: 1px solid black; padding: 10px;">Count</th>
            <th style="border: 1px solid black; padding: 10px;">Total</th>
          </thead>
          <tbody>
          @foreach ($bodyMessage->data as $item)
            @if ($loop->index==0)
              <tr>
                <td  style="border: 1px solid black; padding: 10px; text-align:center;">{{ $loop->index+1 }}</td>
                <td  style="border: 1px solid black; padding: 10px;">{{ carbondatetimeToYmd($item->date) }}</td>
                <td  style="border: 1px solid black; padding: 10px;">{{ $item->vendor }}</td>
                <td  style="border: 1px solid black; padding: 10px; text-align: center;">{{ $item->count }}</td>
                <td  style="border: 1px solid black; padding: 10px; text-align: center;" rowspan="{{ count($bodyMessage->data) }}">{{ $bodyMessage->total }}</td>
              </tr>
            @else
              <tr>
                <td  style="border: 1px solid black; padding: 10px; text-align:center;">{{ $loop->index+1 }}</td>
                <td  style="border: 1px solid black; padding: 10px;">{{ carbondatetimeToYmd($item->date) }}</td>
                <td  style="border: 1px solid black; padding: 10px;">{{ $item->vendor }}</td>
                <td  style="border: 1px solid black; padding: 10px; text-align: center;">{{ $item->count }}</td>
              </tr>
            @endif
          @endforeach
          </tbody>
        </table>
      @else
        <h4  style="text-align: center;">No data found!</h4>
      @endif

              

      <br/><br/>

      <p style="margin-top:0px; margin-bottom: 0px;">Thank you,</p>
      <p style="margin-top:0px; margin-bottom: 0px;">Rongila Automated Email System</p>
      <p style="margin-top:0px; margin-bottom: 0px;">{{ $website ?? '' }}</p>

      <br>

      <p style="font-size: 9px; text-align: justify; text-justify:inter-word; color: #CC0000;">
        This e-mail message including any attachment may contain privileged and confidential information. If you are the intended recipient, you are hereby notified that any disclosure, copying, distribution, or the taking of any action in reliance on the contents of this emailed information is strictly prohibited. If you are not the intended recipient, please (i) do not go through this e-mail, (ii) do not forward, print, copy or otherwise disseminate this e-mail, (iii) notify me of the error by a reply to this e-mail, and (iv) delete this e-mail from your computer.

        This e-mail message including any attachment may contain privileged and confidential information. If you are the intended recipient, you are hereby notified that any disclosure, copying, distribution, or the taking of any action in reliance on the contents of this emailed information is strictly prohibited. If you are not the intended recipient, please (i) do not go through this e-mail, (ii) do not forward, print, copy or otherwise disseminate this e-mail, (iii) notify me of the error by a reply to this e-mail, and (iv) delete this e-mail from your computer. 
      </p>
  </body>
</html>