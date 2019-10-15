<div class="row report">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="box-title">{{ ucfirst($title) }}</div>
            </div>

            <div class="box-body">
                <form action="{{ url("/admin/reports/$action/export") }}" filename="{{ Illuminate\Support\Str::camel(strtolower($title)) }}">
                    @csrf

                    <div class="filters">
                        <p>{{ __("Filters") }}</p>
                        {{ $filters }}
                    </div>

                    <div class="order">
                        <p>{{ __("Order") }}</p>
                        <select class="form-control form-control-sm" name="order[column]">
                            @foreach($order as $key => $translation)
                            <option value="{{ $key }}">{{ $translation }}</option>
                            @endforeach()
                        </select>
                        <select class="form-control form-control-sm" name="order[direction]">
                            <option value="ASC">{{ __("Ascendent") }}</option>
                            <option value="DESC" selected>{{ __("Descendent") }}</option>
                        </select>
                    </div>

                    <div class="actions">
                        <p>{{ __("Actions") }}</p>
                        <button type="submit" value="preview" class="btn btn-sm btn-primary">{{ __("Preview") }}</button>
                        <button type="submit" value="export" format="csv" class="btn btn-sm btn-primary">{{ __("Export") }} CSV</button>
                        <button type="submit" value="export" format="pdf" class="btn btn-sm btn-primary">{{ __("Export") }} PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 preview">
        <div class="box">
            <div class="close">×</div>
            <div class="box-body table-responsive">
                <table class="table table-hover"></table>
            </div>
        </div>
    </div>
</div>