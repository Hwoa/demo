@extends('pages.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">ログイン</div>
        <div class="panel-body">
          @if (count($errors) > 0)
            <div class="alert alert-danger">
              <strong> ログインエラーが発生しました。</strong><br><br>
              <ul>
                <li>認証に失敗しました。</li>
              </ul>
            </div>
          @endif

          <form class="form-horizontal" role="form" method="POST" action="{{url('/auth/login')}}">
            {{-- CSRF対策--}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label class="col-md-4 control-label">メールアドレス</label>
              <div class="col-md-6">
                <input type="text" class="form-control" name="email" value="{{ old('email') }}" />
              </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">パスワード</label>
              <div class="col-md-6">
                <input type="password" class="form-control" name="password" value="" />
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="remember"> ログイン情報を記憶する
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary w150" style="margin-right: 15px;">
                  認証
                </button>
              </div>
            </div>
          </form>
        </div><!-- .panel-body -->
      </div><!-- .panel -->
    </div><!-- .col -->
  </div><!-- .row -->
</div><!-- .container-fluid -->
@endsection