
<head>
<title>School | Expense Receipt</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
@php
$getSetting=Helper::getSetting();
//dd($data);
@endphp
<style>
    body {
        border: 2px solid black;
        padding: 10px;
    }
    .tr_border {
        border-top: 2px solid black;
        border-bottom: 2px solid black;
    }
        .img_background_fixed{
      position: relative;
    }
    
    .img_absolute{
        position: absolute;
        top: 89px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        right: 0;
    }
    
    .backhround_img{
        opacity: 0.3;
        width: 34%;
    }
</style>
<body>
@include('print_file.print_header')
<table width="100%" style="border-bottom: 2px solid black;">
   
    <tr>
        <td width="50%"><b>Date: {{date('d-m-Y', strtotime($data[0]['date'])) ?? '' }}</b></td>
        <td width="50%" style="text-align: right;"><b>User: {{ $data[0]['user']['first_name'].' '.$data[0]['user']['last_name'] }}</b></td>
    </tr>
    
</table>

        <table width="100%" style="border-bottom: 2px solid black;">
            <div class="img_background_fixed">
        <div class="img_absolute">
        <img src="{{ env('IMAGE_SHOW_PATH').'/setting/watermark_image/'.$getSetting['watermark_image'] }}" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'/default/rukmanisoft_logo.png' }}'" alt="" class="backhround_img">
        </div>
                    <thead>
                        <tr role="row" style="text-align:center;">
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black;">Sr.NO.</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;"> P A R T I C U L A R S</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Quantity</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;"> Rate</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;"> Amount</th>
                        </tr>
                    </thead>
                  <tbody>
                    @php
                        $sr=1;
                        $totalAmt=0;
                    @endphp
                @foreach($data as $item)
                    @php
                        $totalAmt += $item['amount'];
                    @endphp
                    <tr style="text-align:center;">
                        <td style="border-right: 1px solid black;border-left: 1px solid black;">{{ $sr++ }}</td>
                        <td style="border-right: 1px solid black;">{{ $item['name'] ?? ''  }} </td>
                        <td style="border-right: 1px solid black;">{{ $item['quantity']  }}</td>
                        <td style="border-right: 1px solid black;">{{ $item['rate']  }}</td>
                        <td style="border-right: 1px solid black;">{{ $item['amount']  }}</td>
                    </tr>
                @endforeach
                
            </tbody>
                
            <tfoot>
                <tr style="text-align:center;">
                    <td style="border-right: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;">
                        Total
                    </td>
                    <td style="border-right: 1px solid black; border-top: 1px solid black;"></td>
                    <td style="border-right: 1px solid black; border-top: 1px solid black;"></td>
                    <td style="border-right: 1px solid black; border-top: 1px solid black;"></td>
                    <td style="border-right: 1px solid black; border-top: 1px solid black;">
                        {{ $totalAmt }}
                    </td>
                </tr>

            </tfoot>
            </div>
                  </table>


</table>
<script>
var th_val = ['', 'thousand', 'Lakh', 'Crore', 'trillion'];
// System for uncomment this line for Number of English 
// var th_val = ['','thousand','million', 'milliard','billion'];
 
var dg_val = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
var tn_val = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
var tw_val = ['Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
function toWordsconver(s) {
  s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s))
        return 'not a number ';
    var x_val = s.indexOf('.');
    if (x_val == -1)
        x_val = s.length;
    if (x_val > 15)
        return 'too big';
    var n_val = s.split('');
    var str_val = '';
    var sk_val = 0;
    for (var i = 0; i < x_val; i++) {
        if ((x_val - i) % 3 == 2) {
            if (n_val[i] == '1') {
                str_val += tn_val[Number(n_val[i + 1])] + ' ';
                i++;
                sk_val = 1;
            } else if (n_val[i] != 0) {
                str_val += tw_val[n_val[i] - 2] + ' ';
                sk_val = 1;
            }
        } else if (n_val[i] != 0) {
            str_val += dg_val[n_val[i]] + ' ';
            if ((x_val - i) % 3 == 0)
                str_val += 'Hundred ';
            sk_val = 1;
        }
        if ((x_val - i) % 3 == 1) {
            if (sk_val)
                str_val += th_val[(x_val - i - 1) / 3] + ' ';
            sk_val = 0;
        }
    }
    if (x_val != s.length) {
        var y_val = s.length;
        str_val += 'point ';
        for (var i = x_val + 1; i < y_val; i++)
            str_val += dg_val[n_val[i]] + ' ';
    }
    return str_val.replace(/\s+/g, ' ');
}

var number = document.getElementById( "inNumber" ).innerText;
  var b = parseInt(number);
 var Inwords = toWordsconver(b);
 
 document.getElementById( "inWords" ).innerText=Inwords+ " " + "Only" ;

//alert(Inwords);
</script>
@include('print_file.print_footer')
</body>
<script type="text/javascript">
  window.print();
</script>