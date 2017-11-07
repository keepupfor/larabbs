@extends('layouts.app')
@section('title',isset($topic->id)?'编辑话题':'新建话题')
@section('content')

<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            
            <div class="panel-body">
                <h2 class="text-center">
                    <i class="glyphicon glyphicon-edit"></i>
                    @if($topic->id)
                       编辑话题
                    @else
                        新建话题
                    @endif
                </h2>
            </div>

            @include('common.errors')

            <div class="panel-body">
                @if($topic->id)
                    <form action="{{ route('topics.update', $topic->id) }}" method="POST" accept-charset="UTF-8">
                        <input type="hidden" name="_method" value="PUT">
                @else
                    <form action="{{ route('topics.store') }}" method="POST" accept-charset="UTF-8">
                @endif

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    
                <div class="form-group">
                	<input placeholder="请填写标题" class="form-control" type="text" name="title" id="title-field" value="{{ old('title', $topic->title ) }}" />
                </div> 
                <div class="form-group">
                    <select name="category_id" id="" class="form-control">
                        <option value="" hidden disabled selected>请选择</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <textarea name="body" class="form-control" id="editor" rows="3" placeholder="请填入至少三个字符的内容。" required>{{ old('body', $topic->body ) }}</textarea>{{ old('excerpt', $topic->excerpt ) }}</textarea>
                </div>
                    <div class="well well-sm">
                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 保存</button>
                        <a class="btn btn-link pull-right" href="{{ route('topics.index') }}"><i class="glyphicon glyphicon-backward"></i>  返回</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop

@section('scripts')
    <script type="text/javascript"  src="{{ asset('js/module.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/hotkeys.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/uploader.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/simditor.js') }}"></script>

    <script>
        $(document).ready(function(){
            var editor = new Simditor({
                textarea: $('#editor'),
                upload:{
                    url:'{{ route('topics.upload_image') }}',
                    params:{_token:'{{ csrf_token() }}'},
                    fileKey:'upload_file',
                    connectionCount:3,
                    leaveConfirm:'文件上传中，关闭此页面将取消上传'
                },
                pasteImage:true,
            });
        });
    </script>

@stop