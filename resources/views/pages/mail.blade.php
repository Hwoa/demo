@extends('pages.app')
@section('content')
    <div class="tabbable border-l border-r">
        <div class="tab-content inner-panel">
            <div class="tab-pane active" id="A">
                <div class="a-center pb5 font11" id="data-table">
                @if ($kind == 0 && $is_admin == 1)
                <div class="f-left pb-10">
                    {!! Form::button('新規メール', ['id' => 'btn_add', 'class' => 'btn btn-info mb10', 'data-toggle' => 'modal', 'data-target' => '#sendModal', 'data-reply_id' => '']) !!}
                    {!! Form::button('再度開く', ['id' => 'btn_rescue', 'class' => 'btn btn-default d-none mb10', 'data-toggle' => 'modal', 'data-target' => '#sendModal', 'data-reply_id' => 'rescue']) !!}
                </div>
                @endif
                {!! Form::Hidden('kind', $kind, ['id' => 'kind']) !!}

                <!-- data -->
                <div class="t-center mt-58" id="render"></div>
                    <table id="table_list" class="table table-bordered table-hover table-striped table-condensed table-responsive">
                        <thead>
                        <tr class="cell-top">
                            @if ($kind == 0)
                                <th class="w5p">&nbsp;</th>
                                <th class="w5p">ID</th>
                                <th class="w20p">送信者</th>
                                <th class="w20p">アドレス</th>
                                <th class="w20p">タイトル</th>
                                <th class="w10p">開封</th>
                                <th class="w10p">返信</th>
                                <th class="w10p">受信日</th>
                            @else
                                <th class="w5p">&nbsp;</th>
                                <th class="w5p">ID</th>
                                <th class="w20p">宛先</th>
                                <th class="w20p">アドレス</th>
                                <th class="w30p">タイトル</th>
                                <th class="w20p">送信日</th>
                        @endif
                        <tr/>
                        </thead>
                        <tbody id="mail">
                        </tbody>
                    </table>
                </div> <!-- /table-div -->
            </div> <!-- /tab-pane A -->
        </div> <!-- /tab-content -->
    </div> <!-- /tabbable -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog w100p">
        <div class="modal-content">
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <table id="modal-top" class="table table-bordered table-hover table-striped table-condensed table-responsive">
                            <tbody>
                            <tr>
                                @if ($kind == 0)
                                    <th class="w14p">送信者</th>
                                @else
                                    <th class="w14p">宛先</th>
                                @endif
                                <td class="w86p"><span id="from_name"></span>
                                    <input type="hidden" value="" name="mail_id" id="mail_id" class="edit"/>
                                </td>
                            </tr>
                            <tr>
                                <th class="w14p">アドレス</th>
                                <td class="w86p"><span id="from_address"></span></td>
                            </tr>
                            <tr>
                                <th class="w14p">日時</th>
                                <td class="w86p"><span id="date"></span></td>
                            </tr>
                            <tr>
                                <th class="w14p">タイトル</th>
                                <td class="w86p"><span id="title"></span></td>
                            </tr>
                            <tr>
                                <th class="w14p">内容</th>
                                <td class="w86p"><span id="contents"></span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                @if ($kind == 0)
                    <button type="button" class="btn btn-primary edit" id="reply">返信</button>
                @endif
                <button type="button" class="btn btn-default edit" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel" aria-hidden="true">
    <div class="modal-dialog w100p">
        <div class="modal-content">
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <table id="sendModal-top" class="table table-bordered table-hover table-striped table-condensed table-responsive">
                            <tr>
                                <th class="w14p"></th>
                                <td class="w86p">
                                    <select id="send_to_name" name="send_to_name" class="form-control edit">
                                        @foreach ($userSelect as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="w14p">宛先</th>
                                <td class="w86p">
                                    <input type="text" value="" class="form-control edit" name="send_to_address" id="send_to_address">
                                    <input type="hidden" value="" name="reply_id" id="reply_id" class="edit"/>
                                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"/>
                                </td>
                            </tr>
                            <tr>
                                <th class="w14p">タイトル</th>
                                <td class="w86p">
                                    <input type="text" value="" class="form-control edit" name="send_title" id="send_title">
                                </td>
                            </tr>
                            <tr>
                                <th class="w14p">内容</th>
                                <td class="w86p"><textarea id="send_contents" name="send_contents" class="form-control edit" rows="15"></textarea></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary edit" id="send">送信</button>
                <button type="button" class="btn btn-default edit" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    var today = {{$dt->day}};
    var todayYear = {{$dt->year}};
    var todayMonth = {{$dt->month}};

    var lastDay = null;
    var firstDayOfWeek = null;
    var thisYear = todayYear;
    var thisMonth = todayMonth;
    var picCnt = 0;
    var bindIds = [];
    var isDone = {!!$isDone!!};

    $(document).ready(function(){

        paging(1);
        $('#exampleModal').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget);
            var mail_id = button.data('mail_id');
            var kind = $("#kind").val();
            var modal = $(this);
            if (mail_id != '') {
                fnBlocking("読み込み中");
                var param = "id=" + mail_id;
                picCnt = 0;
                $.ajax({
                    url: '{{url("/api/mail/detail")}}',
                    data: param,
                }).done(function(data){
                    var mailList = data['mailList'];
                    $("#mail_id").val(mailList['id']);
                    $("#title").text(mailList['title']);
                    $("#date").text(mailList['date']);
                    $("#contents").html(mailList['contents']);
                    if (kind == 0) {
                        $("#from_address").text(mailList['from_address']);
                        $("#from_name").text(mailList['from_name']);
                    } else {
                        $("#from_address").text(mailList['to_address']);
                        $("#from_name").text(mailList['to_name']);
                    }

                    if (kind == 0) {
                        $("#id_" + mailList['id']).html($("#id_" + mailList['id']).html().replace('<b>', '').replace('</b>', '<b>'));
                        $("#from_name_" + mailList['id']).html($("#from_name_" + mailList['id']).html().replace('<b>', '').replace('</b>', '<b>'));
                        $("#from_address_" + mailList['id']).html($("#from_address_" + mailList['id']).html().replace('<b>', '').replace('</b>', '<b>'));
                        $("#title_" + mailList['id']).html($("#title_" + mailList['id']).html().replace('<b>', '').replace('</b>', '<b>'));
                        $("#is_read_" + mailList['id']).html(isDone[1]);
                        $("#is_reply_" + mailList['id']).html($("#is_reply_" + mailList['id']).html().replace('<b>', '').replace('</b>', '<b>'));
                        $("#date_" + mailList['id']).html($("#date_" + mailList['id']).html().replace('<b>', '').replace('</b>', '<b>'));
                    }

                }).always(function(data) {
                    fnUnBlocking();
                });
            }
        });

        $('#sendModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var reply_id = button.data('reply_id');
            var type = typeof reply_id;

            if (typeof reply_id === "undefined") {
                reply_id = $("#mail_id").val();
            }

            var modal = $(this);
            if (reply_id != 'rescue') {
                $("#btn_rescue").show();
                if (reply_id != '') {
                    $("#send_to_address").val($("#from_address").text());
                    $("#send_to_name").val($("#from_name").text());
                    $("#send_title").val('Re:' + $("#title").text());
                    var contentsHtml = $("#contents").html();
                    var contentsArr = contentsHtml.split('<br>');
                    var str = "";
                    for (var i in contentsArr) {
                        if (i < contentsArr.length - 1) {
                            str += ">> " + unEscapeHTML(contentsArr[i]) + "\n";
                        }
                    }
                    if (str.trim() != "") {
                        str += "\n";
                    }
                    $("#send_contents").val(str);
                } else {
                    fnResetModal();
                }
            }
        });

        $("#reply").click(function(){
            $("#exampleModal").modal("hide");
            $('#sendModal').modal("show");
            $("#reply_id").val($("#mail_id").val());
            $("#btn_rescue").show();
        });
        $("#send").click(function(){
            if (!window.confirm("送信します。よろしいですか？")) {
                return false;
            }

            var contents = $("#send_contents").val();
            var param = "reply_id=" + $("#reply_id").val();
            param += "&to_address=" + $("#send_to_address").val();
            param += "&to_name=" + $("#send_to_name").val();
            param += "&title=" + $("#send_title").val();
            param += "&contents=" + contents;

            fnBlocking("送信中");
            $.ajax({
                url: '{{url("/api/mail/send/")}}',
                type: "POST",
                data: param,
            }).done(function(data){
                if (typeof data["errors"] !== "undefined") {
                    var errors = data["errors"];
                    var msg = "";
                    for (var i in errors) {
                        msg += errors[i] + "\n";
                    }
                    alert(msg);
                } else {
                    console.log(data['error_address']);
                    alert("送信が完了しました。");
                    var errors = data["error_address"];
                    if (errors.length > 0) {
                        var msg = "メールアドレスに問題があるため、下記の方にはメールを送信できませんでした。";
                        for (var i in errors) {
                            msg += errors[i] + "\n";
                        }
                        alert(msg);
                    }
                    $("#btn_rescue").hide();
                    $("#sendModal").modal("hide");
                }
            }).fail(function(){
                alert("メールを送信することができませんでした。");
            }).always(function(){
                fnUnBlocking();
                paging(1);
            });
        });
        $("#send_to_name").change(function(){
            var val = $(this).val();
            $("#send_to_address").val("");
            $("#send_to_address").attr('readonly', true);
            if (val == "manual") {
                $("#send_to_address").attr('readonly', false);
                $("#send_to_address").val("");
            } else if (val != 'all') {
                var param = "id=" + val;

                fnBlocking("宛先取得中");
                $.ajax({
                    url: '{{url("/api/mail/user/")}}',
                    type: "GET",
                    data: param,
                }).done(function(data){
                    $("#send_to_address").val(data['user']['email']);
                }).always(function(){
                    fnUnBlocking();
                });
            }
        });
    });

    function paging(page) {
        fnBlocking("読み込み中");
        var param = "page=" + page;
        var kind = $("#kind").val();
        param += "&kind=" + kind;
        $.ajax({
            url: '{{url("/api/mail/list")}}',
            data: param,
        }).done(function(data){
            var list = data['list'];
            $("#render").html(data['render']);
            $("#all_cnt").text(data['allCnt']);
            $("#page_cnt").text(list.length);
            $("#mail > tr").remove();
            for (var i in list) {
                var head = '';
                var foot = '';
                if (list[i]['is_read'] == 0 && kind == 0) {
                    var head = '<b>';
                    var foot = '</b>';
                }
                var row = '<tr>';
                row += '<td><input type="button" class="btn-mini btn-info" data-toggle="modal" data-target="#exampleModal" data-mail_id="' + list[i]['id'] + '" value="詳細" /></td>';
                row += '<td id="id_' + list[i]['id'] + '">' + head + list[i]['id'] + foot + '</td>';
                if (kind == 0) {
                    row += '<td id="from_name_' + list[i]['id'] + '">' + head + list[i]['from_name'] + foot + '</td>';
                    row += '<td id="from_address_' + list[i]['id'] + '">' + head + list[i]['from_address'] + foot + '</td>';
                    row += '<td id="title_' + list[i]['id'] + '">' + head + list[i]['title'] + foot + '</td>';
                    row += '<td id="is_read_' + list[i]['id'] + '">' + head + isDone[list[i]['is_read']] + foot + '</td>';
                    row += '<td id="is_reply_' + list[i]['id'] + '">' + head + isDone[list[i]['is_reply']] + foot + '</td>';
                } else {
                    if (list[i]['to_name'] == '' && list[i]['to_address'] == '') {
                        list[i]['to_name'] = '全員に送信';
                        list[i]['to_address'] = '全員に送信';
                    }
                    row += '<td id="from_name_' + list[i]['id'] + '">' + head + list[i]['to_name'] + foot + '</td>';
                    row += '<td id="from_address_' + list[i]['id'] + '">' + head + list[i]['to_address'] + foot + '</td>';
                    row += '<td id="title_' + list[i]['id'] + '">' + head + list[i]['title'] + foot + '</td>';
                }
                row += '<td id="date_' + list[i]['id'] + '">' + head + list[i]['date'] + foot + '</td>';

                row += '</tr>';
                $("#mail").append(row);
            }
        }).always(function(data) {
            fnUnBlocking();
        });
    }

    function fnResetModal() {
        $("#reply_id").val("");
        $("#send_to_address").val("");
        $("#send_to_address").attr('readonly', false);
        $("#send_to_name").val("manual");
        $("#send_title").val("");
        $("#send_contents").val("");
    }

</script>
@endsection
