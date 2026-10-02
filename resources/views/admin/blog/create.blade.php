@extends('adminlte::page')

@section('title', 'Nuevo post')

@section('css')
    <style>
        #pell-blog-content-mount.pell {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            background: #fff;
        }
        #pell-blog-content-mount .pell-content {
            min-height: 280px;
            outline: none;
        }
        .pell-blog-content-wrapper.is-invalid #pell-blog-content-mount.pell {
            border-color: #dc3545;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pell@1.0.6/dist/pell.min.css" crossorigin="anonymous">
@endsection

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Nuevo post</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            @include('admin.blog.form', ['action' => route('admin.blogs.store')])
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/pell@1.0.6/dist/pell.min.js" crossorigin="anonymous"></script>
    <script>
        (function initPellBlogContent() {
            var mount = document.getElementById('pell-blog-content-mount');
            var ta = document.getElementById('content');
            if (!mount || !ta || typeof window.pell === 'undefined' || typeof window.pell.init !== 'function') {
                return;
            }
            var editor = window.pell.init({
                element: mount,
                onChange: function (html) {
                    ta.value = html;
                },
                defaultParagraphSeparator: 'p',
                actions: [
                    'bold',
                    'italic',
                    'underline',
                    'heading2',
                    'paragraph',
                    'quote',
                    'line',
                    'olist',
                    'ulist',
                    'link',
                ],
            });
            editor.content.innerHTML = ta.value || '';
            var form = ta.closest('form');
            if (form) {
                form.addEventListener('submit', function () {
                    ta.value = editor.content.innerHTML;
                });
            }
        })();
    </script>
@stop
