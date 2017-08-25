@extends('pages.app')
@section('content')

      <div class="tabbable border-l border-r">
        <div class="tab-content inner-panel">
          <div class="tab-pane active" id="A">
            <div class="a-center pb5 font11" id="data-table">
                @if ($is_admin == 1)
                    <div class="f-left pb-10">
                        {!! Form::button('新規追加', ['id' => 'btn_add', 'class' => 'btn btn-info mb10', 'data-toggle' => 'modal', 'data-target' => '#editModal', 'data-whatever' => '']) !!}
                    </div>
                @endif
              <!-- data -->
              <div class="t-center mt-58" id="render"></div>
              <table id="table_list" class="table table-bordered table-hover table-striped table-condensed table-responsive">
                <thead id="tr_practice">
                  <tr>
                    <th rowspan="4" class="text-center w10p">-</th>
                    <th rowspan="4" class="text-center w30p">氏名</th>
                    <th id="th_practice" class="text-center w60p">練習日</th>
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
                  <th class="w20p">参加</th>
                  <td class="w80p">
                      @foreach ($presences_text as $key => $value)
                      <input type="radio" name="presence" id="presence_{{$key}}"  value="{{$key}}"/><label for="presence_{{$key}}">{{$value}}</label>&nbsp;
                      @endforeach
                  </td>
                </tr>
                <tr><th class="w20p">詳細</th>
                  <td class="w80p"><textarea style="width:100%;" rows="10" maxlength="500" name="description" id="description" class="edit"></textarea>
                    <input type="hidden" name="user_id" id="user_id" value="" class="edit" />
                    <input type="hidden" name="plan_id" id="plan_id" value="" class="edit" />
                  </td>
                </tr>
              </table>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary edit" id="save">保存</button>
        <button type="button" class="btn btn-default edit" id="close" data-dismiss="modal">閉じる</button>
      </div>
    </div>
  </div>
</div>

  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-body">
                  <form>
                      <div class="form-group">
                          <table class="table table-bordered table-hover table-striped table-condensed table-responsive">
                              <tr>
                                  <th class="w20p">権限</th>
                                  <td class="w80p" id="tdIsAdmin"></td>
                              </tr>
                              <tr>
                                  <th class="w20p">氏名</th>
                                  <td class="w80p"><input type="text" name="edit_user_name" id="edit_user_name" class="form-control w100p user_edit" value="" />
                                  <input type="hidden" name="edit_user_id" id="edit_user_id" class="user_edit" value="" /></td>
                              </tr>
                              <tr>
                                  <th class="w20p" style="border-bottom:none;">メールアドレス</th>
                                  <td class="w80p" style="border-bottom:none;"><input type="text" name="edit_user_email" id="edit_user_email" class="form-control w100p user_edit" value="" /></td>
                              </tr>
                              <tr>
                                  <th class="w20p" style="border-top:none;">確認</th>
                                  <td class="w80p" style="border-top:none;"><input type="text" name="edit_user_email_confirmation" id="edit_user_email_confirmation" class="form-control w100p user_edit" value="" /></td>
                              </tr>
                              <tr>
                                  <th class="w20p" style="border-bottom:none;">パスワード</th>
                                  <td class="w80p" style="border-bottom:none;"><input type="password" name="edit_user_password" id="edit_user_password" class="form-control w100p user_edit" value="" />
                              </tr>
                              <tr>
                                  <th class="w20p" style="border-top:none;">確認</th>
                                  <td class="w80p" style="border-top:none;"><input type="password" name="edit_user_password_confirmation" id="edit_user_password_confirmation" class="form-control w100p user_edit" value="" />
                                      <span class="red" id="password_alert"><br/>※パスワード変更時のみ入力してください</span></td></td>
                              </tr>
                          </table>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-primary edit" id="submit">保存</button>
                  <button type="button" class="btn btn-danger edit d-none" id="delete">削除</button>
                  <button type="button" class="btn btn-default edit" id="close" data-dismiss="modal">閉じる</button>
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-body">
                  <form>
                      <div class="form-group">
                          <table class="table table-bordered table-hover table-striped table-condensed table-responsive">
                              <tr>
                                  <td id="map" class="w100p h300"></td>
                              </tr>
                          </table>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default edit" id="close" data-dismiss="modal">閉じる</button>
              </div>
          </div>
      </div>
  </div>

@endsection

@section('template')
@endsection

@section('js')
<script type="text/javascript">

var presences = {!!$presences!!};
var colors = {!!$colors!!};
var userId = {!!$user_id!!};
var is_admin_text = {!!$is_admin_text!!};
var is_admin = {!! $is_admin !!};

$(document).ready(function(){
    $('#mapModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('whatever');
        param = "&id=" + recipient;
        $("#map").html("");
        if (recipient !== "") {
            fnBlocking("読み込み中");
            $.ajax({
                url: '{{url("api/index/map")}}',
                data: param,
            }).done(function(data){
                var list = data['list'];
                if (list != null) {
                    var map = list['map'];
                    map = map.replace('<iframe', '<iframe width="100%" height="100%"');
                    $("#map").html(map);
                }
            }).always(function(data) {
                fnUnBlocking();
            });
        }
    });

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
                url: '{{url("api/index/edit")}}',
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

    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('whatever');
        param = "id=" + recipient;
        fnResetUserModal();
        $("#edit_user_id").val(recipient);
        if (recipient !== "") {
            $("#password_alert").show();
            fnBlocking("読み込み中");
            $.ajax({
                url: '{{url("api/index/user")}}',
                data: param,
            }).done(function(data){
                var list = data['list'];
                if (list != null) {
                    $("#edit_user_name").val(list['name']);
                    $("#edit_user_email").val(list['email']);
                    $("#edit_user_email_confirmation").val(list['email']);
                    if (is_admin == 1) {
                        var selectStr = '<select name="edit_user_is_admin" id="edit_user_is_admin" class="form-control user_edit" value="" >';
                        for (var i in is_admin_text) {
                            selectStr += '<option value="' + i + '">' + is_admin_text[i] + '</option>';
                        }
                        selectStr += '</select>';
                        $("#tdIsAdmin").html(selectStr);
                        $("#edit_user_is_admin").val(list['is_admin']);
                        $("#delete").show();
                    } else {
                        $("#tdIsAdmin").text(is_admin_text[list['is_admin']]);
                    }
                }
            }).always(function(data) {
                fnUnBlocking();
            });
        } else {
            $("#password_alert").hide();
            var selectStr = '<select name="edit_user_is_admin" id="edit_user_is_admin" class="form-control user_edit" value="" >';
            for (var i in is_admin_text) {
                selectStr += '<option value="' + i + '">' + is_admin_text[i] + '</option>';
            }
            selectStr += '</select>';
            $("#tdIsAdmin").html(selectStr);
        }
    });

    $("#submit").click(function(){
        var param = "";
        $(".user_edit").each(function(){
            if (this.value !== "") {
                param += "&" + this.name.replace('edit_user_', '') +"=" + this.value.trim();
                $(this).val(this.value.trim());
            }
        });
        fnBlocking("更新中");
        $.ajax({
            url: '{{url("api/index/submit")}}',
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
                $("#editModal").modal("hide");
                paging(1);
            }
        })
    });
    $("#save").click(function(){
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
            url: '{{url("api/index/save")}}',
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
                //alert("更新が完了しました。");
                $("#exampleModal").modal("hide");
                paging(1);
            }
        })
    });
    $("#delete").click(function(){
        if (!window.confirm("削除します。よろしいですか？")) {
            return false;
        }
        var param = "&id=" + $("#edit_user_id").val();
        fnBlocking("削除中");
        $.ajax({
            url: '{{url("api/index/delete")}}',
            data: param,
            type: 'POST'
        }).done(function(){
            fnUnBlocking();
            //alert("削除が完了しました。");
            $("#editModal").modal("hide");
            paging(1);
        })
    });
    paging(1);
})
function paging(page) {

    var perPage = 3;

    $(".tr_practices").remove();
    $("#th_practice").attr("colspan", "");
    $("#table_list > tbody > tr").remove();

    fnBlocking("読み込み中");
    var param = "page=" + page
    $.ajax({
        url: '{{url("api/index/lists")}}',
        data: param,
    }).done(function(data){
        var plans = data['plans'];
        $("#th_practice").attr("colspan", perPage);
        var dateTr = "<tr class='tr_practices'>";
        var nameTr = "<tr class='tr_practices'>";
        var timeTr = "<tr class='tr_practices'>";
        for (var i in plans) {
            dateTr += '<th  class="text-center w15p" style="border-bottom:none;border-top:none;">' + parseInt(plans[i]['start'].substring(5, 7)) + '/' + parseInt(plans[i]['start'].substring(8, 10)) + '</th>';
            nameTr += '<th  class="text-center" style="border-bottom:none;border-top:none;">';
            if (plans[i]['place_name'] != "" && plans[i]['map'] != null && plans[i]['map'] != "") {
                nameTr += '<a href="javascript:void(0);" data-toggle="modal" data-target = "#mapModal" data-whatever="' + plans[i]['place_id'] + '">' + plans[i]['place_name'] + '</a>';
            }
            nameTr += '</th>';
            var stt = plans[i]['start'].substring(11, 16);
            var end = plans[i]['end'].substring(11, 16);
            timeTr += '<th  class="text-center" style="border-top:none;">' + stt + '～' + end + '</th>';
        }

        if (plans.length < perPage) {
            for (var i = 0; i < perPage - plans.length; i++) {
                dateTr += '<th  class="text-center w15p" style="border-bottom:none;border-top:none;"></th>';
                nameTr += '<th  class="text-center" style="border-bottom:none;border-top:none;"></th>';
                timeTr += '<th  class="text-center" style="border-top:none;"></th>';
            }
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
            if (userId == list[i]['id'] || is_admin == 1) {
                trStr += '<td><input type="button" class="btn-sm btn-primary" data-toggle="modal" data-target = "#editModal" data-whatever="' + list[i]['id'] + '" value="編集" /></td>';
            } else {
                trStr += '<td>' + list[i]['id'] + '</td>';
            }

            if (list[i]['is_admin'] == 1) {
                trStr += '<td><b>' + list[i]['name'] + '<br/>【管理】</b></td>';
            } else {
                trStr += '<td>' + list[i]['name'] + '</td>';
            }

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
            if (plans.length < perPage) {
                for (var i = 0; i < perPage - plans.length; i++) {
                    trStr += '<td></td>';
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
        fnUnBlocking();
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

function fnResetUserModal() {
    $("#delete").hide();
    $("#edit_user_id").val("");
    $("#edit_user_name").val("");
    $("#edit_user_email").val("");
    $("#edit_user_email_confirmation").val("");
    $("#edit_user_password").val("");
    $("#tdIsAdmin").html("");
}
</script>
@endsection
