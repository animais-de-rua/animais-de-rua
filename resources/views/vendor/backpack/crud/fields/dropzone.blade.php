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
                url: "{{ $field['upload-url'] }}",
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
                previewTemplate: '<div class="dz-preview dz-file-preview"><input type="hidden" name="{{ $field['name'] }}[]" class="dropzone-filename-field" /><div class="dz-image-no-hover"><img data-dz-thumbnail /></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-error-message"><span data-dz-errormessage></span></div><div class="dz-success-mark"><svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"><title>Check</title><defs></defs><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"><path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path></g></svg></div><div class="dz-error-mark"><svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"><title>Error</title><defs></defs><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"><g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475"><path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path></g></g></svg></div></div>'
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