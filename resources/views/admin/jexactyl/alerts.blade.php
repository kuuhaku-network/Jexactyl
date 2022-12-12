@extends('layouts.admin')
@include('partials/admin.jexactyl.nav', ['activeTab' => 'alerts'])

@section('title')
    Alert Settings
@endsection

@section('content-header')
    <h1>Jexactylアラート<small>UIでクライアントにアラートを送信。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理者</a></li>
        <li class="active">Jexactyl</li>
    </ol>
@endsection

@section('content')
    @yield('jexactyl::nav')
    <div class="row">
        <div class="col-xs-12">
            <form action="{{ route('admin.jexactyl.alerts') }}" method="POST">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">アラート設定 <small>現在のアラートに関する設定を行う。</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">アラートの種類</label>
                                <div>
                                <select name="alert:type" class="form-control">
                                        <option @if ($type == 'success') selected @endif value="success">成功</option>
                                        <option @if ($type == 'info') selected @endif value="info">情報</option>
                                        <option @if ($type == 'warning') selected @endif value="warning">警告</option>
                                        <option @if ($type == 'danger') selected @endif value="danger">危険</option>
                                    </select>
                                    <p class="text-muted"><small>これは、フロントエンドに送信されるアラートのタイプです。</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">アラートメッセージ</label>
                                <div>
                                    <input type="text" class="form-control" name="alert:message" value="{{ $message }}" />
                                    <p class="text-muted"><small>これは、アラートが含まれるテキストです。</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! csrf_field() !!}
                <button type="submit" name="_method" value="PATCH" class="btn btn-default pull-right">Update Alert</button>
            </form>
            <form action="{{ route('admin.jexactyl.alerts.remove') }}" method="POST">
                {!! csrf_field() !!}
                <button type="submit" name="_method" value="POST" class="btn btn-danger pull-right" style="margin-right: 8px;">Remove Alert</button>
            </form>
        </div>
    </div>
@endsection
