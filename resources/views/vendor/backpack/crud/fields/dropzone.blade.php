@push('crud_fields_styles')
    <link rel="stylesheet" href="{{ asset('css/vendor/dropzone.min.css') }}">
    <style>
        .dropzone-target {
            border: 2px dashed #d2d6de;
            color: #333;
            font-size: 1.2em;
            font-weight: 700;
            padding: 2em;
        }

        .dropzone-previews {
            margin-bottom: 10px;
            border: 1px solid #d2d6de;
            padding: 6px;
        }

        .dropzone.dz-drag-hover {
            background: #ececec;
            border: 2px dashed #999;
        }

        .dz-message {
            text-align: left;
        }

        .dropzone .dz-preview .dz-image-no-hover {
            border-radius: 4px;
            cursor: move;
            display: block;
            height: 120px;
            overflow: hidden;
            position: relative;
            width: 120px;
            z-index: 10;
            background-size: cover;
            background-position: center;
        }
        .dropzone .dz-preview .dz-success-mark,
        .dropzone .dz-preview .dz-error-mark {
            margin-top: -37px;
        }
        .dropzone .dz-preview .dz-image-no-hover {
            margin: 8px;
            box-shadow: 0px 1px 6px rgba(0,0,0,0.35);
            background-color: #000;
        }
        .dz-error-message {
            width: 154px!important;
            top: 160px!important;
        }
        .dropzone .dz-preview .dz-error-message:after {
            left: 70px;
        }
    </style>
@endpush

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ $field['label'] }}</label>
    <div id="{{ $field['name'] }}-existing" class="dropzone dropzone-previews">
    	@if (isset($field['value']) && count($field['value']))
        	@foreach($field['value'] as $key => $file_path)
        		<div class="dz-preview dz-image-preview dz-complete">
                    <input type="hidden" name="{{ $field['name'] }}[]" value="{{ $file_path }}" />
                    <div class="dz-image-no-hover" style="background-image:url('{{ "/".$file_path }}');" /></div>
                    <a class="dz-remove dz-remove-existing" href="javascript:undefined;">{{ trans('backpack::dropzone.remove_file') }}</a>
                </div>
        	@endforeach
        @endif
    </div>
    <div id="{{ $field['name'] }}-dropzone" class="dropzone dropzone-target"></div>
    <div id="{{ $field['name'] }}-hidden-input" class="hidden"></div>
</div>

@push('crud_fields_scripts')
    <script src="{{ asset('js/vendor/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/vendor/sortable.min.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;

        $( document ).ready(function() {
            $("div#{{ $field['name'] }}-dropzone").dropzone({
                url: "{{ $field['upload-url'] . "/" . ($field['thumb'] ?? 0) . "/" . ($field['size'] ?? 0) . "/" . ($field['quality'] ?? 0) }}",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                dictDefaultMessage: "{{ trans('backpack::dropzone.drop_to_upload') }}",
                dictFallbackMessage: "{{ trans('backpack::dropzone.not_supported') }}",
                dictFallbackText: null,
                dictInvalidFileType: "{{ trans('backpack::dropzone.invalid_file_type') }}",
                dictFileTooBig: "{{ trans('backpack::dropzone.file_too_big') }}",
                dictResponseError: "{{ trans('backpack::dropzone.response_error') }}",
                dictMaxFilesExceeded: "{{ trans('backpack::dropzone.max_files_exceeded') }}",
                dictCancelUpload: "{{ trans('backpack::dropzone.cancel_upload') }}",
                dictCancelUploadConfirmation: "{{ trans('backpack::dropzone.cancel_upload_confirmation') }}",
                dictRemoveFile: "{{ trans('backpack::dropzone.remove_file') }}",
                success: function (file, response, request) {

                    if (response.success) {
                        $(file.previewElement).find('.dropzone-filename-field').val(response.filename);
                    }
                    $(".dz-remove").off('click').on('click', removeFiles);
                },
                addRemoveLinks: true,
                previewsContainer: "div#{{ $field['name'] }}-existing",
                hiddenInputContainer: "div#{{ $field['name'] }}-hidden-input",
                previewTemplate: '<div class="dz-preview dz-file-preview"><input type="hidden" name="{{ $field['name'] }}[]" class="dropzone-filename-field" /><div class="dz-image-no-hover"><img data-dz-thumbnail /></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-error-message"><span data-dz-errormessage></span></div><div class="dz-success-mark"><svg version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" width="54px" height="54px" viewBox="0 0 54 54"><path fill="#FFFFFF" opacity="0.8" d="M27,0C12.1,0,0,12.1,0,27s12.1,27,27,27s27-12.1,27-27S41.9,0,27,0z M43.3,23.3l-17,17 c-1.6,1.6-4.1,1.6-5.6,0c-0.1-0.1-0.2-0.2-0.3-0.3l-8.5-8.5c-1.6-1.6-1.6-4.1,0-5.7c1.6-1.6,4.1-1.6,5.7,0l5.9,5.9l14.2-14.2 c1.6-1.6,4.1-1.6,5.7,0C44.9,19.2,44.9,21.8,43.3,23.3z"/></svg></div><div class="dz-error-mark"><svg version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" width="54px" height="54px" viewBox="0 0 54 54"><path fill="#FFFFFF" opacity="0.8" d="M27,0C12.1,0,0,12.1,0,27s12.1,27,27,27c14.9,0,27-12.1,27-27S41.9,0,27,0z M38.3,34.7 c1.6,1.6,1.6,4.1,0,5.7c-1.6,1.6-4.1,1.6-5.7,0L27,34.7l-5.7,5.7c-1.6,1.6-4.1,1.6-5.7,0c-1.6-1.6-1.6-4.1,0-5.7l5.7-5.7l-5.7-5.7 c-1.6-1.6-1.6-4.1,0-5.7c1.6-1.6,4.1-1.6,5.7,0l5.7,5.7l5.7-5.7c1.6-1.6,4.1-1.6,5.7,0c1.6,1.6,1.6,4.1,0,5.7L32.7,29L38.3,34.7z"/></svg></div></div>'
            });

            var el = document.getElementById('{{ $field['name'] }}-existing');
            var sortable = new Sortable(el, {
                group: "{{ $field['name'] }}-sortable",
                handle: ".dz-preview",
                draggable: ".dz-preview",
                scroll: false,
            });

            $('dz-remove-existing, .dz-remove').on('click', removeFiles);

            function removeFiles(e) {
                e.preventDefault();
                e.stopPropagation();
                var element = $(this).closest('.dz-preview').remove();

                var file = $(this).siblings('input').val();

                if(file) {
                    $.ajax({
                        headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                        accepts: {json: 'application/json'},
                        url: '{{ $field['upload-url'] }}/remove',
                        method: 'POST',
                        data: {
                            id: {{ isset($id) ? $id : '0' }},
                            filepath: file,
                        }
                    }).done(function(e) {
                        $(element).closest('.dz-preview').remove();
                    })
                } else {
                    $(this).closest('.dz-preview').remove();
                }

            }
        });
    </script>
@endpush
