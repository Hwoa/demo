@extends('layout')
@section('content')
      <ul class="nav nav-tabs">
        <li class="active"><a href="#A" data-toggle="tab">出欠一覧</a></li>
        <li><a href="#B" data-toggle="tab">練習日程</a></li>
        <li><a href="#C" data-toggle="tab">練習会場</a></li>
      </ul>
      <div class="tabbable border-l border-r">
        <div class="tab-content inner-panel">
          <div class="tab-pane active" id="A">
            <div class="a-center pb5 font11" id="data-table">
              <br/>
              <!-- data -->
              <div class="t-center mt-58" id="render"></div>
              <table id="table_list" class="table table-bordered table-hover table-striped table-condensed table-responsive">
                <thead id="tr_practice">
                  <tr>
                    <th width="30%" rowspan="4" class="text-center"></th>
                    <th width="70%" id="th_practice" class="text-center">練習日</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div> <!-- /table-div -->
          </div> <!-- /tab-pane A -->
        </div> <!-- /tab-content -->
      </div> <!-- /tabbable -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
          <form>
            <div class="form-group">
              <table class="table table-bordered table-hover table-striped table-condensed table-responsive">
                <tr>
                  <th>参加</th>
                  <td>
                      @foreach ($presences_text as $key => $value)
                      <input type="radio" name="presence" id="presence_{{$key}}"  value="{{$key}}"/><label for="presence_{{$key}}">{{$value}}</label>&nbsp;
                      @endforeach
                </tr>
                <tr><th>詳細</th>
                  <td><textarea style="width:100%;" rows="10" maxlength="500" name="description" id="description" class="edit"></textarea>
                    <input type="hidden" name="user_id" id="user_id" value="" class="edit" />
                    <input type="hidden" name="plan_id" id="plan_id" value="" class="edit" />
                  </td>
                </tr>
              </table>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary wait_load" id="save">保存</button>
        <button type="button" class="btn btn-default wait_load" id="close" data-dismiss="modal">閉じる</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('template')
@endsection

@section('js')
<script type="text/javascript">

var blnLoad = true;
var presences = {!!$presences!!};
var colors = {!!$colors!!};
var userId = {!!$user_id!!};

$(document).ready(function(){
    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('whatever');
        var recipients = recipient.split('_');
        param = "&user_id=" + recipients[0] + "&plan_id=" + recipients[1];
        fnResetModal();
        $("#user_id").val(recipients[0]);
        $("#plan_id").val(recipients[1]);
        if (recipient !== "") {
            fnBlocking("読み込み中");
            $.ajax({
                url: '{{url("/edit")}}',
                data: param,
            }).done(function(data){
                var list = data['list'];
                if (list != null) {
                    $("#description").val(list['description']);
                    $("#presence_" + list['presence_id']).prop('checked', true);
                }
            }).always(function(data) {
                fnUnBlocking();
            });
        }
    });
    $("#save").click(function(){
        if (!window.confirm("更新します。よろしいですか？")) {
            return false;
        }
        var param = "";
        $(".edit").each(function(){
            if (this.value !== "") {
                param += "&" + this.name +"=" + this.value;
            }
        });
        for (var i in presences) {
            if ($("#presence_" + i).prop("checked") == true) {
                param += "&presence_id=" + i;
            }
        }
        fnBlocking("更新中");
        $.ajax({
            url: '{{url("/save")}}',
            data: param,
            type: 'POST'
        }).done(function(data){
            if (typeof data["errors"] !== "undefined") {
                var err = data["errors"];
                var msg = "";
                for (var i in err) {
                    msg += err[i] + "\n";
                }
                alert(msg);
                fnUnBlocking();
            } else {
                fnUnBlocking();
                alert("更新が完了しました。");
                $("#exampleModal").modal("hide");
                paging(1);
            }
        })
    });
    paging(1);
})
function paging(page) {

    $(".tr_practices").remove();
    $("#th_practice").attr("colspan", "");
    $("#table_list > tbody > tr").remove();

    if (blnLoad) fnBlocking("読み込み中");
    var param = "page=" + page
    $.ajax({
        url: '{{url("/lists")}}',
        data: param,
    }).done(function(data){
        var plans = data['plans'];
        $("#th_practice").attr("colspan", plans.length);
        var dateTr = "<tr class='tr_practices'>";
        var nameTr = "<tr class='tr_practices'>";
        var timeTr = "<tr class='tr_practices'>";
        for (var i in plans) {
            dateTr += '<th  class="text-center">' + parseInt(plans[i]['start'].substring(5, 7)) + '/' + parseInt(plans[i]['start'].substring(8, 10)) + '</th>';
            nameTr += '<th  class="text-center">' + plans[i]['place_name'] + '</th>';
            var stt = plans[i]['start'].substring(11, 16);
            var end = plans[i]['end'].substring(11, 16);
            timeTr += '<th  class="text-center">' + stt + ' ～ ' + end + '</th>';
        }

        dateTr += "</tr>";
        nameTr += "</tr>";
        timeTr += "</tr>";

        $("#tr_practice").append(dateTr);
        $("#tr_practice").append(nameTr);
        $("#tr_practice").append(timeTr);

        var list = data["list"];

        for (var i in list) {
        var trStr = '<tr>';
            trStr += '<td>' + list[i]['name'] + '</td>';
            for (var j in plans) {
                // user_id + planId
                var tdId = list[i]['id'] + "_" + plans[j]['id'];
                trStr += '<td id="' + tdId + '" class="font16">';
                if (userId == list[i]['id']) {
                    trStr += '<input type="button" data-toggle="modal" data-target = "#exampleModal" data-whatever="' + tdId + '" value="-" /></td>';
                } else {
                    trStr += '-</td>';
                }


            }
            trStr += '</tr>';
            $("#table_list > tbody").append(trStr);
        }

        var render = data["render"];
        $("#render").html(render);

        var schedules = data['schedules']
        for (var i in schedules) {
            var scheduleId = schedules[i]['user_id'] + "_" + schedules[i]['plan_id'];

            if (userId == schedules[i]['user_id']) {
                var presenceText = '<input type="button" data-toggle="modal" data-target = "#exampleModal" data-whatever="' + scheduleId + '" value="' + presences[schedules[i]['presence_id']] + '" />';
            } else if(schedules[i]['description'] != "") {
                var presenceText = '<a href="javascript:void(0);" data-toggle="tooltip" data-placement="bottom" data-container="body" title="' + schedules[i]['description'] + '">' + presences[schedules[i]['presence_id']] + '</a>';
            } else {
                var presenceText = presences[schedules[i]['presence_id']];
            }
            $("#" + scheduleId).attr("class", colors[schedules[i]['presence_id']] + " font16");
            $("#" + scheduleId).html(presenceText);
        }
        $('[data-toggle=tooltip]').tooltip();


    }).always(function() {
        if (blnLoad) $.unblockUI();
        $("[name='my-checkbox']").bootstrapSwitch();
    });
}

function fnResetModal() {
    for (var i in presences) {
        $("#presence_" + i).prop('checked', false);
    }
    $("#description").val("");
    $("#user_id").val("");
    $("#plan_id").val("");
}
</script>
@endsection
