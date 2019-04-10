<div class="tab-pane fade {{ $lang == 'th' ? 'in active' : ''}}" id="tab-panel-{{ $lang }}">
    <div class="table-responsive">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="form-group">
                        <p style="margin: 8px auto;">Name({{ strtoupper($lang) }}) <span class="ic-red">*</span></p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        {{ Form::text('data[name][' . $lang . ']', $data['name'][$lang], [
                            'id'            => 'name-' . $lang,
                            'class'         => 'form-control',
                            'readonly'      => false,
                            'placeholder'   => 'Enter payment name',
                            'maxlength'     => 35,
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="form-group">
                        <p style="margin: 8px auto;">Subtitle({{ strtoupper($lang) }})</p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        {{ Form::text('data[subtitle][' . $lang . ']', $data['subtitle'][$lang], [
                            'id'            => 'subtitle-' . $lang,
                            'class'         => 'form-control',
                            'readonly'      => false,
                            'placeholder'   => 'Enter payment subtitle',
                            'maxlength'     => 60,
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="form-group">
                        <p style="margin: 8px auto;">Description({{ strtoupper($lang) }}) <span class="ic-red">*</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <div class="form-group">
                        <textarea id="description_{{ $lang }}" name="data[description][{{ $lang }}]" ng-model="">{{ $data['description'][$lang] }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>