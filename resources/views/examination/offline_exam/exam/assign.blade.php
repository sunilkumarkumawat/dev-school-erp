@php
$classType = Helper::classTypeExam();
$getsubject = Helper::getSubject();
@endphp
@extends('layout.app')
@section('content')

<div class="content-wrapper">

    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary flex_items_toggel">
                            <h3 class="card-title"><i class="nav-icon fas fa fa-tag"></i> &nbsp;Assign Exam To Class :: {{$data->name ?? ''}} </h3>
                            <div class="card-tools">
                                <a href="{{url('view/exam')}}" class="btn btn-primary  btn-sm"><i
                                        class="fa fa-arrow-left"></i> <span class="Display_none_mobile">{{
                                        __('messages.Back') }}</span> </a>
                            </div>

                        </div>
                        <form class="p-3" action="{{ url('assign/exam/'.$data->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="edit_id" id="edit_id">
                            
                            <div id="examWrapper">
                            
                                <div class="row examRow">
                                    
                                    <div class="col-md-3">
                                        <label>Class *</label>
                                        <select name="class_type_id[]" class="form-control" required>
                                            <option value="">Select</option>
                                            @foreach($classType as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            
                                    <div class="col-md-2">
                                        <label>Total Marks *</label>
                                        <input type="text" name="total_marks[]" class="form-control" placeholder="Enter Total Marks" required>
                                    </div>
                            
                                    <div class="col-md-2">
                                        <label>Exam Date *</label>
                                        <input type="date" name="exam_date[]" class="form-control">
                                    </div>
                            
                                    <div class="col-md-2">
                                        <label>Result Date *</label>
                                        <input type="date" name="result_declaration_date[]" class="form-control">
                                    </div>
                            
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-success addRow">+</button>
                                    </div>
                            
                                </div>
                            
                            </div>
                            
                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>

                        <div class="row m-3 pb-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Class</th>
                                        <th>Total Marks</th>
                                        <th>Exam Date</th>
                                        <th>Result Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            
                                <tbody>
                                    @foreach($AssignExam as $key => $item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->class_name }}</td>
                                        <td>{{ $item->total_marks }}</td>
                                        <td>{{ $item->exam_date }}</td>
                                        <td>{{ $item->result_declaration_date }}</td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-primary btn-xs editAssign tooltip1"
                                                title1="Edit Student"
                                                data-id="{{$item->id}}"
                                                data-class="{{$item->class_type_id}}"
                                                data-total_marks="{{$item->total_marks}}"
                                                data-exam_date="{{$item->exam_date}}"
                                                data-result_declaration_date="{{$item->result_declaration_date}}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-xs deleteAssign tooltip1"
                                                title1="Delete"
                                                data-assign_id="{{$item->id}}"
                                                data-toggle="modal"
                                                data-target="#deleteModal">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="modal fade" id="deleteModal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Delete Conformation</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="{{ url('assign/delete/exam') }}" method="post">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" id="exam_id" name="exam_id"
                                                value="{{$data->id ?? ''}}">
                                            <input type="hidden" id="assign_id" name="assign_id">
                                            Are You Sure ?
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Sumbit</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    $(document).ready(function () {
        $('.deleteAssign').click(function () {
            var assign_id = $(this).data('assign_id');
            $('#assign_id').val(assign_id);
        });
    })
</script>

<script>
$(document).ready(function(){

    $(document).on('click','.addRow',function(){

        let row = `
        <div class="row examRow mt-2">
            <div class="col-md-3">
                <select name="class_type_id[]" class="form-control" required>
                    <option value="">Select</option>
                    @foreach($classType as $type)
                    <option value="{{$type->id}}">{{$type->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <input type="number" name="total_marks[]" class="form-control" required>
            </div>

            <div class="col-md-2">
                <input type="date" name="exam_date[]" class="form-control" required>
            </div>

            <div class="col-md-2">
                <input type="date" name="result_declaration_date[]" class="form-control" required>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger removeRow">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        `;

        $('#examWrapper').append(row);
    });

    $(document).on('click','.removeRow',function(){
        $(this).closest('.examRow').remove();
    });

});
</script>
<script>

$(document).on('click','.editAssign',function(){

    let id = $(this).data('id');
    let class_id = $(this).data('class');
    let total_marks = $(this).data('total_marks');
    let exam_date = $(this).data('exam_date');
    let result_declaration_date = $(this).data('result_declaration_date');

    $('#examWrapper').html('');

    let row = `
    <div class="row examRow">
        <div class="col-md-3">
            <label>Class *</label>
            <select name="class_type_id[]" class="form-control classSelect" required>
                <option value="">Select</option>
                @foreach($classType as $type)
                <option value="{{$type->id}}">{{$type->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Total Marks *</label>
            <input type="number" name="total_marks[]" class="form-control" value="${total_marks}" required>
        </div>

        <div class="col-md-2">
            <label>Exam Date *</label>
            <input type="date" name="exam_date[]" class="form-control" value="${exam_date}" required>
        </div>

        <div class="col-md-2">
            <label>Result Date *</label>
            <input type="date" name="result_declaration_date[]" class="form-control" value="${result_declaration_date}" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-success addRow">+</button>
        </div>
    </div>
    `;

    $('#examWrapper').append(row);

    $('#edit_id').val(id);

    // Set selected class
    $('.classSelect').val(class_id);

    $('html, body').animate({
        scrollTop: $("#examWrapper").offset().top - 100
    }, 500);

});

</script>





@endsection