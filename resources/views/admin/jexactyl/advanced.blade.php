@extends('layouts.admin')
@include('partials/admin.jexactyl.nav', ['activeTab' => 'advanced'])

@section('title')
    Advanced
@endsection

@section('content-header')
    <h1>Advanced<small>パネルの詳細設定を行います。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理者</a></li>
        <li class="active">Jexactyl</li>
    </ol>
@endsection

@section('content')
    @yield('jexactyl::nav')
        <form action="{{ route('admin.jexactyl.advanced') }}" method="POST">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">セキュリティ設定</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="control-label">2要素認証を必須とする</label>
                                    <div>
                                        <div class="btn-group" data-toggle="buttons">
                                            @php
                                                $level = old('pterodactyl:auth:2fa_required', config('pterodactyl.auth.2fa_required'));
                                            @endphp
                                            <label class="btn btn-primary @if ($level == 0) active @endif">
                                                <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="0" @if ($level == 0) checked @endif> 必要なし
                                            </label>
                                            <label class="btn btn-primary @if ($level == 1) active @endif">
                                                <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="1" @if ($level == 1) checked @endif> 管理者のみ
                                            </label>
                                            <label class="btn btn-primary @if ($level == 2) active @endif">
                                                <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="2" @if ($level == 2) checked @endif> 全ユーザー
                                            </label>
                                        </div>
                                        <p class="text-muted"><small>有効にすると、選択したグループに属するアカウントは、パネルを使用するために 2要素認証を有効にする必要があります。</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">reCAPTCHA</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="control-label">ステータス</label>
                                    <div>
                                        <select class="form-control" name="recaptcha:enabled">
                                            <option value="true">有効</option>
                                            <option value="false" @if(old('recaptcha:enabled', config('recaptcha.enabled')) == '0') selected @endif>無効</option>
                                        </select>
                                        <p class="text-muted small">この機能を有効にすると、ログインフォームおよびパスワードリセットフォームは、サイレントキャプチャチェックを行い、必要に応じて可視キャプチャを表示します。</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">Site Key</label>
                                    <div>
                                        <input type="text" required class="form-control" name="recaptcha:website_key" value="{{ old('recaptcha:website_key', config('recaptcha.website_key')) }}">
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">Secret Key</label>
                                    <div>
                                        <input type="text" required class="form-control" name="recaptcha:secret_key" value="{{ old('recaptcha:secret_key', config('recaptcha.secret_key')) }}">
                                        <p class="text-muted small">Used for communication between your site and Google. Be sure to keep it a secret.</p>
                                    </div>
                                </div>
                            </div>
                            @if($warning)
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="alert alert-warning no-margin">
                                            現在、この Panel に同梱されている reCAPTCHA キーを使用しています。セキュリティを向上させるには、お客様のウェブサイトに特別に関連付けられた<a href="https://www.google.com/recaptcha/admin">新しい目に見えない reCAPTCHA キーを生成する</a>ことをお勧めします。
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">HTTP接続</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">接続タイムアウト</label>
                                    <div>
                                        <input type="number" required class="form-control" name="pterodactyl:guzzle:connect_timeout" value="{{ old('pterodactyl:guzzle:connect_timeout', config('pterodactyl.guzzle.connect_timeout')) }}">
                                        <p class="text-muted small">エラーを投げる前に接続が開かれるのを待つ時間を秒単位で指定します。</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">要求タイムアウト</label>
                                    <div>
                                        <input type="number" required class="form-control" name="pterodactyl:guzzle:timeout" value="{{ old('pterodactyl:guzzle:timeout', config('pterodactyl.guzzle.timeout')) }}">
                                        <p class="text-muted small">エラーが発生する前に、リクエストが完了するのを待つ時間（秒）。</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">自動割当作成</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="control-label">ステータス</label>
                                    <div>
                                        <select class="form-control" name="pterodactyl:client_features:allocations:enabled">
                                            <option value="false">無効</option>
                                            <option value="true" @if(old('pterodactyl:client_features:allocations:enabled', config('pterodactyl.client_features.allocations.enabled'))) selected @endif>有効</option>
                                        </select>
                                        <p class="text-muted small">この機能を有効にすると、ユーザーはフロントエンドから自分のサーバーに新しい割当を自動的に作成することができるようになります。</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">開始ポート</label>
                                    <div>
                                        <input type="number" class="form-control" name="pterodactyl:client_features:allocations:range_start" value="{{ old('pterodactyl:client_features:allocations:range_start', config('pterodactyl.client_features.allocations.range_start')) }}">
                                        <p class="text-muted small">自動的に割り当て可能な範囲の開始ポート。</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">終了ポート</label>
                                    <div>
                                        <input type="number" class="form-control" name="pterodactyl:client_features:allocations:range_end" value="{{ old('pterodactyl:client_features:allocations:range_end', config('pterodactyl.client_features.allocations.range_end')) }}">
                                        <p class="text-muted small">自動割り当て可能な範囲の終端ポート。</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ csrf_field() }}
                    <button type="submit" name="_method" value="PATCH" class="btn btn-default pull-right">設定の保存</button>
                </div>
            </div>
        </form>
@endsection
