@php
$getSetting=Helper::getSetting();
//dd($data);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student admission Print</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        max-width: 750px;
        margin:auto
            /* border: 0.5px solid; */
    }

    .student_img {
        width: 80px;
        height: 100;
        margin-top: 5%;
        margin-left: 20%;
        padding-bottom: 10px;

    }

    .row {
        margin-right: 0px;
    }

    .img_background_fixed {
        position: relative;
    }

    .img_absolute {
        position: absolute;
        top: 131px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        right: 0;
    }

    .backhround_img {
        opacity: 0.3;
        width: 47%;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 0px;
    }

    .inner_table th {
        border: 1px solid #000;
        padding: 5px;
        /* background-color: #f2f2f2; */
    }

    .invoice-header {
        margin-bottom: 20px;
        text-align: 'center'
    }

    .inner_table td {
        padding: 5px
    }

    .ltr {
        text-align: left;
        border-right: none !important;
    }

    .rtr {
        text-align: right;
    }

    .etr {
        text-align: end;
    }

    #personal_detail th {
        border: 1px solid #000;
        text-align: left;
        padding: 8px;
        background: #dddddd
    }

    #personal_detail td {
        border: 1px solid #000;
        text-align: left;
        padding: 8px;
    }

    .img_bwm {
        width: 100px;
        text-align: right;
        height: 109px;
        padding: 3px;
        border: 2px dotted black;
        margin-bottom: -13px;
        margin-top: -17px;
    }

    .decl {
        text-align: center;
    }

    .sign_father {
        text-align: end;
    }

    .sign {
        display: flex;
        justify-content: space-between;
    }

    .border_line {
        border-bottom: 1px dotted black;
        width: 60px;

    }

    td {
        height: 23px;
    }

    .school {
        text-align: center;
        font-size: 42px;
        font-family: auto;
        margin-top: -16px;
        margin-bottom: 17px;
    }

    .adres {
        text-align: center;
        margin-top: -20px;
        font-size: 17px;
        font-weight: 600;
    }
    .bg_admission {
        background-color: black;
        color: white;
        font-size: 21px;
        text-align: center;
        padding: 5px;
        border-radius: 10px;
        margin-top: -14px;
    }
    </style>
</head>

<body class='page'>
    <table>
        <tbody>
            <tr>
                <td width='33.33%'>
                    <p>Reg. No. 593/JAI 1998-9</p>
                </td>
                <td width='33.33%' style="text-align:center">
                    <img src="https://i.pinimg.com/736x/dd/ca/07/ddca07c82291b80c43100cdc8036996b.jpg"
                        style="width:67px" alt="">
                </td>
                <td width='33.33%'>
                    <p>DEO/SEC/JAI/09-10/351-377</p>
                    <p>DEO/PRI/JAI/M/F-68/35/17-18</p>
                </td>

            </tr>
            <tr>
                <td colspan="12">
                    <h1 class="school">{{$getSetting['name'] ?? ''}}</h1>
                    <p class="adres">{{$getSetting['address'] ?? ''}}</p>
                </td>
            </tr>

        </tbody>

    </table>
    <table>
        <tbody>
            <tr>
                <td width="7%">MEDIUM:</td>
                <td width="15%" style="border-bottom: 1px dotted black">{{ $data['medium'] ?? ''}}</td>
                <td></td>
                <td width="7%">S.R.No:</td>
                <td width="15%" style="border-bottom: 1px dotted black">{{ $data['admissionNo'] ?? '' }}</td>
            </tr>
        </tbody>
    </table>
    <table style="margin-top: 9px;">
        <tbody>
            <tr>
                <td width="8%">SESSION :</td>
                <td width="15%" style="border-bottom: 1px dotted black">{{ $data['from_year'] ?? ''}} - {{ $data['to_year'] ?? ''}}</td>
                <td width="12%"></td>
                <td width="22%">
                    <div class="bg_admission">
                        Admission Form
                    </div>
                </td>
                <td width="15%"></td>
                <td width="5%">Date</td>
                <td width="15%" style="border-bottom: 1px dotted black">{{date('d-m-Y', strtotime($data['admission_date'])) ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    <table>

        <thead>


            <tr>
                <table style='margin-top: 5px ;border-top:3px solid #6639b5'>

                    <thead>
                        <tr>
                            <table>
                                <thead>
                                    <tr>
                                        <td>
                                            <h4>[ TO BE FILLED IN BLOCK LETTER ]</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width='28%' class='ltr'>1. NAME OF THE STUDENT(a) In English:</td>

                                        <td width='55%' class='' style='border-bottom:1px dotted black'>{{ $data['first_name'] ?? '' }} {{ $data['last_name'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td width='25%' class='etr'> (b) In Hindi:</td>

                                        <td width='55%' class='' style='border-bottom:1px dotted black'></td>
                                    </tr>
                                </thead>
                            </table>
                        </tr>


                        <tr>
                            <table>
                                <thead>
                                    <tr>
                                        <td width='26%' class='ltr'>2. DATE OF BIRTH (a) In English:</td>

                                        <td width='80%' class='' style='border-bottom:1px dotted black'>{{date('d-m-Y', strtotime($data['dob'])) ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td width='25%' class='etr'>(b) In Hindi:</td>

                                        <td width='55%' class='' style='border-bottom:1px dotted black'></td>
                                    </tr>
                                </thead>
                            </table>
                        </tr>



                        <table style="margin-top: 10px;">

                            <thead>
                                <tr>
                                    <td width='10%' class='ltr'>3. NATIONALITY:</td>
                                    <td width='10%' style='border-bottom:1px dotted black'></td>

                                    <td width='10%' class='ltr'>RELIGION:</td>
                                    <td width='10%' style='border-bottom:1px dotted black'>{{$data['religion'] ?? ''}}</td>

                                    <td width='10%' class='rtr'>CASTE:</td>
                                    <td width='10%' style='border-bottom:1px dotted black'>{{$data['caste_category'] ?? ''}}</td>

                                    <td width='10%' class='rtr'>CATEGORY:</td>
                                    <td width='10%' style='border-bottom:1px dotted black'>{{$data['category'] ?? ''}}</td>
                                </tr>

                            </thead>

                        </table>


                        <table>
                            <thead>
                                <tr style="margin-top: 10px;">
                                    <td width='16%' class='ltr'>4. BLOOD GROUP:</td>

                                    <td width='85%' class='' style='border-bottom:1px dotted black'>{{$data['blood_group'] ?? ''}}</td>
                                </tr>
                            </thead>


                        </table>


            </tr>




            <tr>
                <table style=' margin-top:15px'>

                    <thead>
                        <tr>
                            <td width='29%' class='ltr'>5. FATHER'S'S NAME(a) In English:</td>

                            <td width='43%' class='' style='border-bottom:1px dotted black'>{{ $data['father_name'] ?? '-' }}</td>

                            <td width='8%' class='rtr'>MOB NO:</td>
                            <td width='45%' class='' style='border-bottom:1px dotted black'>{{ $data['father_mobile'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class='etr'> (b) In Hindi:</td>

                            <td colspan="3" class='' style='border-bottom:1px dotted black'></td>
                        </tr>
                    </thead>



                </table>


            </tr>
            <tr>
                <table style=' margin-top:15px'>

                    <thead>
                        <tr>
                            <td width='29%' class='ltr'>6. MOTHER'S NAME(a) In English:</td>

                            <td width='43%' class='' style='border-bottom:1px dotted black'>{{ $data['mother_name'] ?? '-' }}</td>

                            <td width='8%' class='rtr'>MOB NO:</td>
                            <td width='45%' class='' style='border-bottom:1px dotted black'>{{ $data['mother_mob'] ?? ''}}</td>
                        </tr>
                        <tr>
                            <td class='etr'> (b) In Hindi:</td>

                            <td colspan="3" class='' style='border-bottom:1px dotted black'></td>
                        </tr>
                    </thead>



                </table>


            </tr>


            <tr>
                <table>
                    <thead>
                        <tr>
                            <td width='30%' class='ltr'>7. GAURDIAN'S NAME(a) In English:</td>

                            <td width='70%' class='' style='border-bottom:1px dotted black'>{{ $data['guardian_name'] ?? ''}}</td>
                        </tr>
                        <tr>
                            <td width='25%' class='etr'> (b) In Hindi:</td>

                            <td width='55%' class='' style='border-bottom:1px dotted black'></td>
                        </tr>
                    </thead>
                </table>
            </tr>
            <tr>
                <table>
                    <thead>
                        <tr>
                            <td width='15%' class='ltr'>8. ADDRESS:</td>

                            <td width='850%' class='' style='border-bottom:1px dotted black'>{{ $data['address'] ?? ''}}</td>
                        </tr>
                    </thead>
                </table>
            </tr>
            <tr>
                <table>
                    <thead>
                        <tr>
                            <td width='16%' class='ltr'>9. Relation with the student:</td>

                            <td width='57%' class='' style='border-bottom:1px dotted black'>{{$data['relation_student'] ?? ''}}</td>
                        </tr>
                    </thead>
                </table>
            </tr>
            <tr>
                <table>
                    <thead>
                        <tr>
                            <td width='17%' class='ltr'>10.Class in which admission is to be taken:</td>

                            <td width='33%' class='' style='border-bottom:1px dotted black'>{{ $data['ClassTypes']['name'] ?? '-' }}</td>
                        </tr>
                    </thead>
                </table>
            </tr>
            <tr>
                <table>
                    <thead>
                        <tr>
                            <td width='17%' class='ltr'>11.Name of the school where studied last year:</td>

                            <td width='28%' class='' style='border-bottom:1px dotted black'>{{$data['school_namestudied_last_year'] ?? ''}}</td>
                        </tr>
                    </thead>
                </table>
            </tr>



            </tr>
        </thead>
    </table>

    <p style='text-align:center; line-height:20px;margin-top:25px'>

        <b>DECLEARATION BY FATHER/GUARDIAN</b></br>
    <table>
        <tr>
            <td>I</td>
            <td class="border_line" style="width: 45%">{{ $data['first_name'] ?? '' }} {{ $data['last_name'] ?? '' }}</td>
            <td>, want to get the said student admitted in your school.</td>
        </tr>
    </table>
    <p>(a) I Will be bound to deposit the tuiyion fee, examination fee, admission and transport fee of this Student on
        time. I will not put any pressure/allegation on the school regarding any kind of fee dues. In case of any dues
        school will be completely free to take its own action.</p>
    <p>(b) His/her character is good,so long as he is studying in the school, I will be responsible for his/her
        character.</p>
    <p>(c) The Information gives in this letter is completely true and correct and irrevocable.</p>
    <p>(d) I will strictly follow all the rules passed by the institution.</p>

    <h6 class="sign_father">SIGNATURE OF FATHER/GUARDIAN</h6>
    <hr />

    <h4 class="decl" style="margin-bottom: 38px;">FOR OFFICE USE ONLY</h4>

    <div class="sign">
        <h6></h6>
            <img src="{{ env('IMAGE_SHOW_PATH').'/setting/seal_sign/'.$getSetting['seal_sign'] }}" style="width: 17%;height:50px;margin-top: -45px;margin-bottom: -37px;" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'/default/seal.png' }}'">
        
    </div>
    <div class="sign">
        <h6>SIGNATURE OF ADMISSIONS INCHARGE</h6>
        <h6>SIGNATURE OF PRINCIPAL</h6>
    </div>

</body>

</html>