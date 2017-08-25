@extends('pages.app')
@section('content')

    <div class="tabbable border-l border-r">
        <div class="tab-content inner-panel">
            <div class="tab-pane active" id="A">
                <div class="f-left">
                    {!! Form::button('新規追加', ['id' => 'btn_add', 'class' => 'btn btn-info mb10', 'data-toggle' => 'modal', 'data-target' => '#exampleModal', 'data-news_id' => '']) !!}
                </div>
                <br class="clearfix"/>
                <div class="a-center pb5 font11" id="data-table">
                    <!-- data -->
                    <div class="t-center mt-58" id="render"></div>
                    <table id="table_list" class="table table-bordered table-hover table-striped table-condensed table-responsive">
                        <thead id="tr_practice">
                        <tr>
                            <th class="text-center w10p"></th>
                            <th class="text-center w50p">会場名称</th>
                            <th class="text-center w40p">最終更新日</th>
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
                                    <th class="w20p">名前</th>
                                    <td class="w80p">
                                        <input type="text" name="edit_name" id="edit_name" class="form-control w200 edit" value="" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w20p">地図(GoogleMap)</th>
                                    <td class="w80p">
                                        <textarea style="width:100%;" rows="10" maxlength="500" name="edit_map" id="edit_map" class="edit"></textarea>
                                    </td>
                                </tr>
                                <tr><th class="w20p">詳細</th>
                                    <td class="w80p"><textarea style="width:100%;" rows="10" maxlength="500" name="edit_description" id="edit_description" class="edit"></textarea>
                                        <input type="hidden" name="edit_id" id="edit_id" value="" class="edit" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary edit" id="save">保存</button>
                    <button type="button" class="btn btn-danger edit d-none" id="delete">削除</button>
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

        var blnLoad = true;

        $(document).ready(function(){
            $('#exampleModal').on('show.bs.modal', function (event) {
                fnResetModal();
                var button = $(event.relatedTarget)
                var recipient = button.data('whatever');
                var param = "id=" + recipient;
                $("#edit_id").val(recipient);
                if (recipient !== "") {
                    fnBlocking("読み込み中");
                    $.ajax({
                        url: '{{url("api/place/edit")}}',
                        data: param,
                    }).done(function(data){
                        var list = data['list'];
                        if (list != null) {
                            $("#delete").show();
                            $("#edit_description").val(list['description']);
                            $("#edit_name").val(list['name']);
                            $("#edit_map").val(list['map']);
                        }
                    }).always(function(data) {
                        fnUnBlocking();
                    });
                }
            });
            $("#save").click(function(){
//                if (!window.confirm("更新します。よろしいですか？")) {
//                    return false;
//                }
                var param = "";
                $(".edit").each(function(){
                    if (this.value !== "") {
                        param += "&" + this.name.replace("edit_", "") +"=" + this.value;
                    }
                });
                fnBlocking("更新中");
                $.ajax({
                    url: '{{url("api/place/save")}}',
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
//                        alert("更新が完了しました。");
                        $("#exampleModal").modal("hide");
                        paging(1);
                    }
                })
            });
            $("#delete").click(function(){
                if (!window.confirm("削除します。よろしいですか？")) {
                    return false;
                }
                var param = "&id=" + $("#edit_id").val();
                fnBlocking("削除中");
                $.ajax({
                    url: '{{url("api/place/delete")}}',
                    data: param,
                    type: 'POST'
                }).done(function(){
                    fnUnBlocking();
//                    alert("削除が完了しました。");
                    $("#exampleModal").modal("hide");
                    paging(1);
                })
            });
            paging(1);
        })
        function paging(page) {

            $("#table_list > tbody > tr").remove();

            if (blnLoad) fnBlocking("読み込み中");
            var param = "page=" + page
            $.ajax({
                url: '{{url("api/place/lists")}}',
                data: param,
            }).done(function(data){
                var list = data['list'];
                for (var i in list) {
                var trStr = "<tr>";
                trStr += '<td><input type="button" class="btn_edit btn-sm btn-primary" value="編集" data-toggle="modal" data-target = "#exampleModal" data-whatever="' + list[i]['id'] + '" /></td>';
                trStr += '<td>' + list[i]['name'] + '</td>';
                trStr += '<td>' + list[i]['updated_at'] + '</td>';
                trStr += "</tr>";
                $("#table_list > tbody").append(trStr);
                }
            }).always(function() {
                if (blnLoad) $.unblockUI();
                $("[name='my-checkbox']").bootstrapSwitch();
            });
        }

        function fnResetModal() {
            $("#delete").hide();
            $("#edit_description").val("");
            $("#edit_name").val("");
            $("#edit_map").val("");
            $("#edit_id").val("");
        }
    </script>
@endsection
